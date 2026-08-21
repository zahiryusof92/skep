<?php

namespace Job;

use Area;
use AuditTrail;
use Carbon\Carbon;
use Category;
use City;
use Commercial;
use Country;
use Developer;
use Dun;
use Facility;
use Files;
use HouseScheme;
use LandTitle;
use Management;
use ManagementAgent;
use ManagementDeveloper;
use ManagementJMB;
use ManagementLiquidator;
use ManagementMC;
use ManagementOthers;
use Monitoring;
use OtherDetails;
use Park;
use Parliment;
use Perimeter;
use Residential;
use State;
use Strata;
use UnitMeasure;
use UnitOption;

class ImportFile
{
    public function fire($job, $data)
    {
        $row = $data['row'];
        $company_id = $data['company_id'];
        $status = $data['status'];
        $file_no = $row['1'];
        $user_id = $data['user_id'];
        $audit_message = ' has been updated.';

        $files = Files::where('company_id', $company_id)->where('file_no', $file_no)->where('is_deleted', 0)->first();
        $is_existing_file = !empty($files);
        if (!$files) {
            $files = new Files();
            if ($status == 1 || $status == '1') {
                $files->approved_by = $user_id;
                $files->approved_at = date('Y-m-d H:i:s');
            }
            $audit_message = ' has been imported.';
        }

        // 155. Status
        $is_active = 0;
        if (isset($row['155']) && !empty($row['155'])) {
            $is_active_raw = trim($row['155']);

            if (!empty($is_active_raw)) {
                if (strtolower($is_active_raw) == 'aktif' || strtolower($is_active_raw) == 'active') {
                    $is_active = 1;
                }
            }
        }

        // 157. New File No
        $new_file_no = '';
        if (isset($row['157']) && !empty($row['157'])) {
            $new_file_no = trim($row['157']);
        }

        // If new file no is provided, only update file_no and skip other updates.
        if (!empty($new_file_no)) {
            $files->company_id = $company_id;
            $files->file_no = $new_file_no;
            $files->is_active = $is_active;
            $files->status = $status;
            $files->created_by = $user_id;
            $files->save();

            $remarks = $files->file_no . $audit_message;
            $auditTrail = new AuditTrail();
            $auditTrail->module = "COB File";
            $auditTrail->remarks = $remarks;
            $auditTrail->audit_by = $user_id;
            $auditTrail->save();

            $job->delete();

            return;
        }

        // 3. Year
        $year = '';
        if (isset($row['3']) && !empty($row['3'])) {
            $year = trim($row['3']);
        }

        $files->company_id = $company_id;
        $files->file_no = $file_no;
        if (!$is_existing_file || $this->hasExcelValue($row, '3')) {
            $files->year = $year;
        }
        if (!$is_existing_file || $this->hasExcelValue($row, '155')) {
            $files->is_active = $is_active;
        }
        $files->status = $status;
        $files->created_by = $user_id;
        $create_or_update_file = $files->save();

        if ($create_or_update_file) {
            // 4. Name
            $name = '';
            if (isset($row['4']) && !empty($row['4'])) {
                $name = trim($row['4']);
            }
            // 7. Address 1
            $address1 = '';
            if (isset($row['7']) && !empty($row['7'])) {
                $address1 = trim($row['7']);
            }
            // 8. Address 2
            $address2 = '';
            if (isset($row['8']) && !empty($row['8'])) {
                $address2 = trim($row['8']);
            }
            // 9. Address 3
            $address3 = '';
            if (isset($row['9']) && !empty($row['9'])) {
                $address3 = trim($row['9']);
            }
            // 10. Address 4
            $address4 = '';
            if (isset($row['10']) && !empty($row['10'])) {
                $address4 = trim($row['10']);
            }
            // 11. Postcode
            $postcode = '';
            if (isset($row['11']) && !empty($row['11'])) {
                $postcode = trim($row['11']);
            }
            // 12. City
            $city = '';
            if (isset($row['12']) && !empty($row['12'])) {
                $city_raw = trim($row['12']);

                if (!empty($city_raw)) {
                    $city_query = City::where('description', $city_raw)->where('is_deleted', 0)->first();
                    if ($city_query) {
                        $city = $city_query->id;
                    } else {
                        $city_query = new City();
                        $city_query->description = $city_raw;
                        $city_query->is_active = 1;
                        $city_query->save();

                        $city = $city_query->id;
                    }
                }
            }
            // 13. State
            $state = '';
            if (isset($row['13']) && !empty($row['13'])) {
                $state_raw = trim($row['13']);

                if (!empty($state_raw)) {
                    $state_query = State::where('name', $state_raw)->where('is_deleted', 0)->first();
                    if ($state_query) {
                        $state = $state_query->id;
                    } else {
                        $state_query = new State();
                        $state_query->name = $state_raw;
                        $state_query->is_active = 1;
                        $state_query->save();

                        $state = $state_query->id;
                    }
                }
            }
            // 14. Country
            $country = '';
            if (isset($row['14']) && !empty($row['14'])) {
                $country_raw = trim($row['14']);

                if (!empty($country_raw)) {
                    $country_query = Country::where('name', $country_raw)->where('is_deleted', 0)->first();
                    if ($country_query) {
                        $country = $country_query->id;
                    } else {
                        $country_query = new Country();
                        $country_query->name = $country_raw;
                        $country_query->is_active = 1;
                        $country_query->save();

                        $country = $country_query->id;
                    }
                }
            }
            // 15. Office No.
            $phone_no = '';
            if (isset($row['15']) && !empty($row['15'])) {
                $phone_no = trim($row['15']);
            }
            // 16. Fax No.
            $fax_no = '';
            if (isset($row['16']) && !empty($row['16'])) {
                $fax_no = trim($row['16']);
            }
            // 17. Developer Status
            $dev_status = 0;
            if (isset($row['17']) && !empty($row['17'])) {
                $dev_status_raw = trim($row['17']);

                if (!empty($dev_status_raw)) {
                    if (strtolower($dev_status_raw) == 'aktif' || strtolower($dev_status_raw) == 'active') {
                        $dev_status = 1;
                    }
                }
            }

            // 6. Developer
            $developer = '';
            if (isset($row['6']) && !empty($row['6'])) {
                $developer_raw = trim($row['6']);

                if (!empty($developer_raw)) {
                    $developer_query = Developer::where('name', $developer_raw)->where('is_deleted', 0)->first();
                    if ($developer_query) {
                        $developer = $developer_query->id;
                    } else {
                        $developer_query = new Developer();
                        $developer_query->name = $developer_raw;
                        $developer_query->address1 = $address1;
                        $developer_query->address2 = $address2;
                        $developer_query->address3 = $address3;
                        $developer_query->address4 = $address4;
                        $developer_query->poscode = $postcode;
                        $developer_query->city = $city;
                        $developer_query->state = $state;
                        $developer_query->country = $country;
                        $developer_query->phone_no = $phone_no;
                        $developer_query->fax_no = $fax_no;
                        $developer_query->is_active = 1;
                        $developer_query->save();

                        $developer = $developer_query->id;
                    }
                }
            }

            $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            $is_new_house_scheme = empty($house_scheme);
            if ($is_new_house_scheme) {
                $house_scheme = new HouseScheme();
                $house_scheme->file_id = $files->id;
            }
            $this->applyExcelFields($house_scheme, $row, $is_new_house_scheme, [
                'name' => ['col' => '4', 'val' => $name],
                'developer' => ['col' => '6', 'val' => $developer],
                'address1' => ['col' => '7', 'val' => $address1],
                'address2' => ['col' => '8', 'val' => $address2],
                'address3' => ['col' => '9', 'val' => $address3],
                'address4' => ['col' => '10', 'val' => $address4],
                'poscode' => ['col' => '11', 'val' => $postcode],
                'city' => ['col' => '12', 'val' => $city],
                'state' => ['col' => '13', 'val' => $state],
                'country' => ['col' => '14', 'val' => $country],
                'phone_no' => ['col' => '15', 'val' => $phone_no],
                'fax_no' => ['col' => '16', 'val' => $fax_no],
            ]);
            $house_scheme->is_active = 1;
            $house_scheme->save();

            // 18. Strata Title
            $strata_title = '';
            if (isset($row['18']) && !empty($row['18'])) {
                $strata_raw = trim($row['18']);

                if ($strata_raw == 'Y') {
                    $strata_title = 1;
                }
            }
            // 19. Strata
            $strata_name = '';
            if (isset($row['19']) && !empty($row['19'])) {
                $strata_name = trim($row['19']);
            }
            // 20. Parliament
            $parliament = '';
            if (isset($row['20']) && !empty($row['20'])) {
                $parliament_raw = trim($row['20']);

                if (!empty($parliament_raw)) {
                    $parliament_query = Parliment::where('description', $parliament_raw)->where('is_deleted', 0)->first();
                    if ($parliament_query) {
                        $parliament = $parliament_query->id;
                    } else {
                        $parliament_query = new Parliment();
                        $parliament_query->description = $parliament_raw;
                        $parliament_query->is_active = 1;
                        $parliament_query->save();

                        $parliament = $parliament_query->id;
                    }
                }
            }
            // 21. DUN
            $dun = '';
            if (isset($row['21']) && !empty($row['21'])) {
                $dun_raw = trim($row['21']);

                if (!empty($dun_raw)) {
                    $dun_query = Dun::where('parliament', $parliament)->where('description', $dun_raw)->where('is_deleted', 0)->first();
                    if ($dun_query) {
                        $dun = $dun_query->id;
                    } else {
                        $dun_query = new Dun();
                        $dun_query->parliament = $parliament;
                        $dun_query->description = $dun_raw;
                        $dun_query->is_active = 1;
                        $dun_query->save();

                        $dun = $dun_query->id;
                    }
                }
            }
            // 22. Park
            $park = '';
            if (isset($row['22']) && !empty($row['22'])) {
                $park_raw = trim($row['22']);

                if (!empty($park_raw)) {
                    $park_query = Park::where('dun', $dun)->where('description', $park_raw)->where('is_deleted', 0)->first();
                    if ($park_query) {
                        $park = $park_query->id;
                    } else {
                        $park_query = new Park();
                        $park_query->dun = $dun;
                        $park_query->description = $park_raw;
                        $park_query->is_active = 1;
                        $park_query->save();

                        $park = $park_query->id;
                    }
                }
            }
            // 23. Address 1
            $strata_address1 = '';
            if (isset($row['23']) && !empty($row['23'])) {
                $strata_address1 = trim($row['23']);
            }
            // 24. Address 2
            $strata_address2 = '';
            if (isset($row['24']) && !empty($row['24'])) {
                $strata_address2 = trim($row['24']);
            }
            // 25. Address 3
            $strata_address3 = '';
            if (isset($row['25']) && !empty($row['25'])) {
                $strata_address3 = trim($row['25']);
            }
            // 26. Address 4
            $strata_address4 = '';
            if (isset($row['26']) && !empty($row['26'])) {
                $strata_address4 = trim($row['26']);
            }
            // 27. Postcode
            $strata_postcode = '';
            if (isset($row['27']) && !empty($row['27'])) {
                $strata_postcode = trim($row['27']);
            }
            // 28. City
            $strata_city = '';
            if (isset($row['28']) && !empty($row['28'])) {
                $strata_city_raw = trim($row['28']);

                if (!empty($strata_city_raw)) {
                    $strata_city_query = City::where('description', $strata_city_raw)->where('is_deleted', 0)->first();
                    if ($strata_city_query) {
                        $strata_city = $strata_city_query->id;
                    } else {
                        $strata_city_query = new City();
                        $strata_city_query->description = $strata_city_raw;
                        $strata_city_query->is_active = 1;
                        $strata_city_query->save();

                        $strata_city = $strata_city_query->id;
                    }
                }
            }
            // 29. State
            $strata_state = '';
            if (isset($row['29']) && !empty($row['29'])) {
                $strata_state_raw = trim($row['29']);

                if (!empty($strata_state_raw)) {
                    $strata_state_query = State::where('name', $strata_state_raw)->where('is_deleted', 0)->first();
                    if ($strata_state_query) {
                        $strata_state = $strata_state_query->id;
                    } else {
                        $strata_state_query = new State();
                        $strata_state_query->name = $strata_state_raw;
                        $strata_state_query->is_active = 1;
                        $strata_state_query->save();

                        $strata_state = $strata_state_query->id;
                    }
                }
            }
            // 30. Country
            $strata_country = '';
            if (isset($row['30']) && !empty($row['30'])) {
                $strata_country_raw = trim($row['30']);

                if (!empty($strata_country_raw)) {
                    $strata_country_query = Country::where('name', $strata_country_raw)->where('is_deleted', 0)->first();
                    if ($strata_country_query) {
                        $strata_country = $strata_country_query->id;
                    } else {
                        $strata_country_query = new Country();
                        $strata_country_query->name = $strata_country_raw;
                        $strata_country_query->is_active = 1;
                        $strata_country_query->save();

                        $strata_country = $strata_country_query->id;
                    }
                }
            }
            // 31. Total Block
            $block_no = '';
            if (isset($row['31']) && !empty($row['31'])) {
                $block_no = trim($row['31']);
            }
            // 32. Floor
            $total_floor = '';
            if (isset($row['32']) && !empty($row['32'])) {
                $total_floor = trim($row['32']);
            }
            // 33. Year
            $strata_year = '';
            if (isset($row['33']) && !empty($row['33'])) {
                $strata_year = trim($row['33']);
            }
            // 34. Ownership No
            $ownership_no = '';
            if (isset($row['34']) && !empty($row['34'])) {
                $ownership_no = trim($row['34']);
            }
            // 35. District
            $town = '';
            if (isset($row['35']) && !empty($row['35'])) {
                $town_raw = trim($row['35']);

                if (!empty($town_raw)) {
                    $town_query = City::where('description', $town_raw)->where('is_deleted', 0)->first();
                    if ($town_query) {
                        $town = $town_query->id;
                    } else {
                        $town_query = new City();
                        $town_query->description = $town_raw;
                        $town_query->is_active = 1;
                        $town_query->save();

                        $town = $town_query->id;
                    }
                }
            }
            // 36. Area
            $area = '';
            if (isset($row['36']) && !empty($row['36'])) {
                $area_raw = trim($row['36']);

                if (!empty($area_raw)) {
                    $area_query = Area::where('description', $area_raw)->where('is_deleted', 0)->first();
                    if ($area_query) {
                        $area = $area_query->id;
                    } else {
                        $area_query = new Area();
                        $area_query->description = $area_raw;
                        $area_query->is_active = 1;
                        $area_query->save();

                        $area = $area_query->id;
                    }
                }
            }
            // 37. Total Land Area
            $land_area = '';
            if (isset($row['37']) && !empty($row['37'])) {
                $land_area = trim($row['37']);
            }
            // 38. Total Land Area UOM
            $land_area_unit = '';
            if (!empty($land_area)) {
                if (isset($row['38']) && !empty($row['38'])) {
                    $land_area_unit_raw = trim($row['38']);

                    if (!empty($land_area_unit_raw)) {
                        $land_area_unit_query = UnitMeasure::where('description', $land_area_unit_raw)->where('is_deleted', 0)->first();
                        if ($land_area_unit_query) {
                            $land_area_unit = $land_area_unit_query->id;
                        } else {
                            $land_area_unit_query = new UnitMeasure();
                            $land_area_unit_query->description = $land_area_unit_raw;
                            $land_area_unit_query->is_active = 1;
                            $land_area_unit_query->save();

                            $land_area_unit = $land_area_unit_query->id;
                        }
                    }
                }
            }
            // 39. Lot No.
            $lot_no = '';
            if (isset($row['39']) && !empty($row['39'])) {
                $lot_no = trim($row['39']);
            }
            // 40. Vacant Possession Date
            $vacant_date = '';
            if (isset($row['40']) && !empty($row['40'])) {
                // $vacant_date = trim($row['40']);
                if (!empty($row['40']['date'])) {
                    $vacant_date = Carbon::parse($row['40']['date'])->format('Y-m-d');
                } else {
                    $vacant_date = Carbon::createFromFormat('d/m/Y', trim($row['40']))->format('Y-m-d');
                }
            }
            // 41. Date CCC
            $ccc_date = '';
            if (isset($row['41']) && !empty($row['41'])) {
                // $ccc_date = trim($row['41']);
                if (!empty($row['41']['date'])) {
                    $ccc_date = Carbon::parse($row['41']['date'])->format('Y-m-d');
                } else {
                    $ccc_date = Carbon::createFromFormat('d/m/Y', trim($row['41']))->format('Y-m-d');
                }
            }
            // 42. CCC No.
            $ccc_no = '';
            if (isset($row['42']) && !empty($row['42'])) {
                $ccc_no = trim($row['42']);
            }
            // 43. Land Title
            $land_title = '';
            if (isset($row['43']) && !empty($row['43'])) {
                $land_title_raw = trim($row['43']);

                if (!empty($land_title_raw)) {
                    $land_title_query = LandTitle::where('description', $land_title_raw)->where('is_deleted', 0)->first();
                    if ($land_title_query) {
                        $land_title = $land_title_query->id;
                    } else {
                        $land_title_query = new LandTitle();
                        $land_title_query->description = $land_title_raw;
                        $land_title_query->is_active = 1;
                        $land_title_query->save();

                        $land_title = $land_title_query->id;
                    }
                }
            }
            // 44. Category
            $category = '';
            if (isset($row['44']) && !empty($row['44'])) {
                $category_raw = trim($row['44']);

                if (!empty($category_raw)) {
                    $category_query = Category::where('description', $category_raw)->where('is_deleted', 0)->first();
                    if ($category_query) {
                        $category = $category_query->id;
                    } else {
                        $category_query = new Category();
                        $category_query->description = $category_raw;
                        $category_query->is_active = 1;
                        $category_query->save();

                        $category = $category_query->id;
                    }
                }
            }
            // 45. Perimeter
            $perimeter = '';
            if (isset($row['45']) && !empty($row['45'])) {
                $perimeter_raw = trim($row['45']);

                if (!empty($perimeter_raw)) {
                    $perimeter_query = Perimeter::where('description_en', $perimeter_raw)->where('is_deleted', 0)->first();
                    if ($perimeter_query) {
                        $perimeter = $perimeter_query->id;
                    } else {
                        $perimeter_query = new Perimeter();
                        $perimeter_query->description_en = $perimeter_raw;
                        $perimeter_query->description_my = $perimeter_raw;
                        $perimeter_query->is_active = 1;
                        $perimeter_query->save();

                        $perimeter = $perimeter_query->id;
                    }
                }
            }
            // 46. Total Share Unit
            $total_share_unit = '';
            if (isset($row['46']) && !empty($row['46'])) {
                $total_share_unit = trim($row['46']);
            }
            // 47. Residential
            $is_residential = 0;
            if (isset($row['47']) && !empty($row['47'])) {
                $is_residential_raw = trim($row['47']);

                if (strtolower($is_residential_raw) == 'yes') {
                    $is_residential = 1;
                }
            }
            // 53. Commercial
            $is_commercial = 0;
            if (isset($row['53']) && !empty($row['53'])) {
                $is_commercial_raw = trim($row['53']);

                if (strtolower($is_commercial_raw) == 'yes') {
                    $is_commercial = 1;
                }
            }

            $strata = Strata::where('file_id', $files->id)->first();
            $is_new_strata = empty($strata);
            if ($is_new_strata) {
                $strata = new Strata();
                $strata->file_id = $files->id;
            }
            $this->applyExcelFields($strata, $row, $is_new_strata, [
                'title' => ['col' => '18', 'val' => $strata_title],
                'name' => ['col' => '19', 'val' => $strata_name],
                'parliament' => ['col' => '20', 'val' => $parliament],
                'dun' => ['col' => '21', 'val' => $dun],
                'park' => ['col' => '22', 'val' => $park],
                'address1' => ['col' => '23', 'val' => $strata_address1],
                'address2' => ['col' => '24', 'val' => $strata_address2],
                'address3' => ['col' => '25', 'val' => $strata_address3],
                'address4' => ['col' => '26', 'val' => $strata_address4],
                'poscode' => ['col' => '27', 'val' => $strata_postcode],
                'city' => ['col' => '28', 'val' => $strata_city],
                'state' => ['col' => '29', 'val' => $strata_state],
                'country' => ['col' => '30', 'val' => $strata_country],
                'block_no' => ['col' => '31', 'val' => $block_no],
                'total_floor' => ['col' => '32', 'val' => $total_floor],
                'year' => ['col' => '33', 'val' => $strata_year],
                'town' => ['col' => '35', 'val' => $town],
                'area' => ['col' => '36', 'val' => $area],
                'land_area' => ['col' => '37', 'val' => $land_area],
                'total_share_unit' => ['col' => '46', 'val' => $total_share_unit],
                'land_area_unit' => ['col' => '38', 'val' => $land_area_unit],
                'lot_no' => ['col' => '39', 'val' => $lot_no],
                'ownership_no' => ['col' => '34', 'val' => $ownership_no],
                'date' => ['col' => '40', 'val' => $vacant_date],
                'land_title' => ['col' => '43', 'val' => $land_title],
                'category' => ['col' => '44', 'val' => $category],
                'perimeter' => ['col' => '45', 'val' => $perimeter],
                'ccc_no' => ['col' => '42', 'val' => $ccc_no],
                'ccc_date' => ['col' => '41', 'val' => $ccc_date],
                'is_residential' => ['col' => '47', 'val' => $is_residential],
                'is_commercial' => ['col' => '53', 'val' => $is_commercial],
            ]);
            if ($is_new_strata) {
                $strata->file_url = '';
            }
            $create_strata = $strata->save();

            if ($create_strata) {
                if ($strata->is_residential) {
                    // 48. Total Unit
                    $residential_unit_no = 0;
                    if (isset($row['48']) && !empty($row['48'])) {
                        $residential_unit_no = trim($row['48']);
                    }
                    // 49. Maintenance Fee
                    $residential_mf = '';
                    if (isset($row['49']) && !empty($row['49'])) {
                        $residential_mf = trim($row['49']);
                    }
                    // 50. Maintenance Fee UOM
                    $residential_mf_unit = '';
                    if (!empty($residential_mf)) {
                        if (isset($row['50']) && !empty($row['50'])) {
                            $residential_mf_unit_raw = trim($row['50']);

                            if (!empty($residential_mf_unit_raw)) {
                                $residential_mf_unit_query = UnitOption::where('description', $residential_mf_unit_raw)->where('is_deleted', 0)->first();
                                if ($residential_mf_unit_query) {
                                    $residential_mf_unit = $residential_mf_unit_query->id;
                                } else {
                                    $residential_mf_unit_query = new UnitOption();
                                    $residential_mf_unit_query->description = $residential_mf_unit_raw;
                                    $residential_mf_unit_query->is_active = 1;
                                    $residential_mf_unit_query->save();

                                    $residential_mf_unit = $residential_mf_unit_query->id;
                                }
                            }
                        }
                    }
                    // 51. Singking Fund
                    $residential_sf = '';
                    if (isset($row['51']) && !empty($row['51'])) {
                        $residential_sf = trim($row['51']);
                    }
                    // 52. Singking Fund UOM
                    $residential_sf_unit = '';
                    if (!empty($residential_sf)) {
                        if (isset($row['52']) && !empty($row['52'])) {
                            $residential_sf_unit_raw = trim($row['52']);

                            if (!empty($residential_sf_unit_raw)) {
                                $residential_sf_unit_query = UnitOption::where('description', $residential_sf_unit_raw)->where('is_deleted', 0)->first();
                                if ($residential_sf_unit_query) {
                                    $residential_sf_unit = $residential_sf_unit_query->id;
                                } else {
                                    $residential_sf_unit_query = new UnitOption();
                                    $residential_sf_unit_query->description = $residential_sf_unit_raw;
                                    $residential_sf_unit_query->is_active = 1;
                                    $residential_sf_unit_query->save();

                                    $residential_sf_unit = $residential_sf_unit_query->id;
                                }
                            }
                        }
                    }

                    $residential = Residential::where('file_id', $files->id)->first();
                    $is_new_residential = empty($residential);
                    if ($is_new_residential) {
                        $residential = new Residential();
                        $residential->file_id = $files->id;
                    }
                    $residential->strata_id = $strata->id;
                    $this->applyExcelFields($residential, $row, $is_new_residential, [
                        'unit_no' => ['col' => '48', 'val' => $residential_unit_no],
                        'maintenance_fee' => ['col' => '49', 'val' => $residential_mf],
                        'maintenance_fee_option' => ['col' => '50', 'val' => $residential_mf_unit],
                        'sinking_fund' => ['col' => '51', 'val' => $residential_sf],
                        'sinking_fund_option' => ['col' => '52', 'val' => $residential_sf_unit],
                    ]);
                    if ($is_new_residential || $this->hasExcelValue($row, '48')) {
                        $residential->under_ten_units = (!empty($residential_unit_no) && $residential_unit_no < 10) ? true : false;
                    }
                    $residential->save();
                }

                if ($strata->is_commercial) {
                    // 54. Total Unit
                    $commercial_unit_no = 0;
                    if (isset($row['54']) && !empty($row['54'])) {
                        $commercial_unit_no = trim($row['54']);
                    }
                    // 55. Maintenance Fee
                    $commercial_mf = '';
                    if (isset($row['55']) && !empty($row['55'])) {
                        $commercial_mf = trim($row['55']);
                    }
                    // 56. Maintenance Fee UOM
                    $commercial_mf_unit = '';
                    if (!empty($commercial_mf)) {
                        if (isset($row['56']) && !empty($row['56'])) {
                            $commercial_mf_unit_raw = trim($row['56']);

                            if (!empty($commercial_mf_unit_raw)) {
                                $commercial_mf_unit_query = UnitOption::where('description', $commercial_mf_unit_raw)->where('is_deleted', 0)->first();
                                if ($commercial_mf_unit_query) {
                                    $commercial_mf_unit = $commercial_mf_unit_query->id;
                                } else {
                                    $commercial_mf_unit_query = new UnitOption();
                                    $commercial_mf_unit_query->description = $commercial_mf_unit_raw;
                                    $commercial_mf_unit_query->is_active = 1;
                                    $commercial_mf_unit_query->save();

                                    $commercial_mf_unit = $commercial_mf_unit_query->id;
                                }
                            }
                        }
                    }
                    // 57. Singking Fund
                    $commercial_sf = '';
                    if (isset($row['57']) && !empty($row['57'])) {
                        $commercial_sf = trim($row['57']);
                    }
                    // 58. Singking Fund UOM
                    $commercial_sf_unit = '';
                    if (!empty($commercial_sf)) {
                        if (isset($row['58']) && !empty($row['58'])) {
                            $commercial_sf_unit_raw = trim($row['58']);

                            if (!empty($commercial_sf_unit_raw)) {
                                $commercial_sf_unit_query = UnitOption::where('description', $commercial_sf_unit_raw)->where('is_deleted', 0)->first();
                                if ($commercial_sf_unit_query) {
                                    $commercial_sf_unit = $commercial_sf_unit_query->id;
                                } else {
                                    $commercial_sf_unit_query = new UnitOption();
                                    $commercial_sf_unit_query->description = $commercial_sf_unit_raw;
                                    $commercial_sf_unit_query->is_active = 1;
                                    $commercial_sf_unit_query->save();

                                    $commercial_sf_unit = $commercial_sf_unit_query->id;
                                }
                            }
                        }
                    }

                    $commercial = Commercial::where('file_id', $files->id)->first();
                    $is_new_commercial = empty($commercial);
                    if ($is_new_commercial) {
                        $commercial = new Commercial();
                        $commercial->file_id = $files->id;
                    }
                    $commercial->strata_id = $strata->id;
                    $this->applyExcelFields($commercial, $row, $is_new_commercial, [
                        'unit_no' => ['col' => '54', 'val' => $commercial_unit_no],
                        'maintenance_fee' => ['col' => '55', 'val' => $commercial_mf],
                        'maintenance_fee_option' => ['col' => '56', 'val' => $commercial_mf_unit],
                        'sinking_fund' => ['col' => '57', 'val' => $commercial_sf],
                        'sinking_fund_option' => ['col' => '58', 'val' => $commercial_sf_unit],
                    ]);
                    if ($is_new_commercial || $this->hasExcelValue($row, '54')) {
                        $commercial->under_ten_units = (!empty($commercial_unit_no) && $commercial_unit_no < 10) ? true : false;
                    }
                    $commercial->save();
                }

                $facility = Facility::where('file_id', $files->id)->first();
                if (empty($facility)) {
                    $facility = new Facility();
                    $facility->file_id = $files->id;
                }
                $facility->strata_id = $strata->id;
                $facility->save();
            }

            // 60. Management Developer
            $is_developer = 0;
            if (isset($row['60']) && !empty($row['60'])) {
                $is_developer_raw = trim($row['60']);

                if (strtolower($is_developer_raw) == 'yes') {
                    $is_developer = 1;
                }
            }

            // 73. Liquidator
            $liquidator = 0;
            if (isset($row['73']) && !empty($row['73'])) {
                $liquidator_raw = trim($row['73']);

                if (strtolower($liquidator_raw) == 'yes') {
                    $liquidator = 1;
                }
            }

            // 86. JMB
            $is_jmb = 0;
            if (isset($row['86']) && !empty($row['86'])) {
                $is_jmb_raw = trim($row['86']);

                if (strtolower($is_jmb_raw) == 'yes') {
                    $is_jmb = 1;
                }
            }

            // 101. MC
            $is_mc = 0;
            if (isset($row['101']) && !empty($row['101'])) {
                $is_mc_raw = trim($row['101']);

                if (strtolower($is_mc_raw) == 'yes') {
                    $is_mc = 1;
                }
            }

            // 116. Agent
            $is_agent = 0;
            if (isset($row['116']) && !empty($row['116'])) {
                $is_agent_raw = trim($row['116']);

                if (strtolower($is_agent_raw) == 'yes') {
                    $is_agent = 1;
                }
            }

            // 130. Others
            $is_others = 0;
            if (isset($row['130']) && !empty($row['130'])) {
                $is_others_raw = trim($row['130']);

                if (strtolower($is_others_raw) == 'yes') {
                    $is_others = 1;
                }
            }

            // 143. No Management
            $no_management = false;
            if (isset($row['143']) && !empty($row['143'])) {
                $no_management_raw = trim($row['143']);

                if (strtolower($no_management_raw) == 'yes') {
                    $no_management = true;
                }
            }

            $under_10_units = false;
            $under_10_units_remarks = '';

            // 144. Date Start
            $date_start = '';
            if (isset($row['144']) && !empty($row['144'])) {
                if (!empty($row['144']['date'])) {
                    $date_start = Carbon::parse($row['144']['date'])->format('Y-m-d');
                } else {
                    $date_start = Carbon::createFromFormat('d/m/Y', trim($row['144']))->format('Y-m-d');
                }
            }

            // 145. Date End
            $date_end = '';
            if (isset($row['145']) && !empty($row['145'])) {
                if (!empty($row['145']['date'])) {
                    $date_end = Carbon::parse($row['145']['date'])->format('Y-m-d');
                } else {
                    $date_end = Carbon::createFromFormat('d/m/Y', trim($row['145']['date']))->format('Y-m-d');
                }
            }

            // 146. Bankruptcy
            $bankruptcy = false;
            if (isset($row['146']) && !empty($row['146'])) {
                $bankruptcy_raw = trim($row['146']);

                if (strtolower($bankruptcy_raw) == 'yes') {
                    $bankruptcy = true;
                }
            }

            // 147. Bankruptcy remarks
            $bankruptcy_remarks = '';
            if (isset($row['147']) && !empty($row['147'])) {
                $bankruptcy_remarks = trim($row['147']);
            }

            $management = Management::where('file_id', $files->id)->first();
            $is_new_management = empty($management);
            if ($is_new_management) {
                $management = new Management();
                $management->file_id = $files->id;
            }

            $this->applyExcelFields($management, $row, $is_new_management, [
                'is_developer' => ['col' => '60', 'val' => $is_developer],
                'liquidator' => ['col' => '73', 'val' => $liquidator],
                'is_jmb' => ['col' => '86', 'val' => $is_jmb],
                'is_mc' => ['col' => '101', 'val' => $is_mc],
                'is_agent' => ['col' => '116', 'val' => $is_agent],
                'is_others' => ['col' => '130', 'val' => $is_others],
                'no_management' => ['col' => '143', 'val' => $no_management],
                'start' => ['col' => '144', 'val' => $date_start],
                'end' => ['col' => '145', 'val' => $date_end],
                'bankruptcy' => ['col' => '146', 'val' => $bankruptcy],
                'bankruptcy_remarks' => ['col' => '147', 'val' => $bankruptcy_remarks],
            ]);
            if ($is_new_management) {
                $management->under_10_units = $under_10_units;
                $management->under_10_units_remarks = $under_10_units_remarks;
            }
            $create_management = $management->save();

            if ($create_management) {
                /** Create Developer */
                if ($management->is_developer) {
                    // 61. Name
                    $developer_name = '';
                    if (isset($row['61']) && !empty($row['61'])) {
                        $developer_name = trim($row['61']);
                    }
                    // 62. Address 1
                    $developer_address1 = '';
                    if (isset($row['62']) && !empty($row['62'])) {
                        $developer_address1 = trim($row['62']);
                    }
                    // 63. Address 2
                    $developer_address2 = '';
                    if (isset($row['63']) && !empty($row['63'])) {
                        $developer_address2 = trim($row['63']);
                    }
                    // 64. Address 3
                    $developer_address3 = '';
                    if (isset($row['64']) && !empty($row['64'])) {
                        $developer_address3 = trim($row['64']);
                    }
                    // 65. Address 4
                    $developer_address4 = '';
                    if (isset($row['65']) && !empty($row['65'])) {
                        $developer_address4 = trim($row['65']);
                    }
                    // 66. Postcode
                    $developer_postcode = '';
                    if (isset($row['66']) && !empty($row['66'])) {
                        $developer_postcode = trim($row['66']);
                    }
                    // 67. City
                    $developer_city = '';
                    if (isset($row['67']) && !empty($row['67'])) {
                        $developer_city_raw = trim($row['67']);

                        if (!empty($developer_city_raw)) {
                            $developer_city_query = City::where('description', $developer_city_raw)->where('is_deleted', 0)->first();
                            if ($developer_city_query) {
                                $developer_city = $developer_city_query->id;
                            } else {
                                $developer_city_query = new City();
                                $developer_city_query->description = $developer_city_raw;
                                $developer_city_query->is_active = 1;
                                $developer_city_query->save();

                                $developer_city = $developer_city_query->id;
                            }
                        }
                    }
                    // 68. State
                    $developer_state = '';
                    if (isset($row['68']) && !empty($row['68'])) {
                        $developer_state_raw = trim($row['68']);

                        if (!empty($developer_state_raw)) {
                            $developer_state_query = State::where('name', $developer_state_raw)->where('is_deleted', 0)->first();
                            if ($developer_state_query) {
                                $developer_state = $developer_state_query->id;
                            } else {
                                $developer_state_query = new State();
                                $developer_state_query->name = $developer_state_raw;
                                $developer_state_query->is_active = 1;
                                $developer_state_query->save();

                                $developer_state = $developer_state_query->id;
                            }
                        }
                    }
                    // 69. Country
                    $developer_country = '';
                    if (isset($row['69']) && !empty($row['69'])) {
                        $developer_country_raw = trim($row['69']);

                        if (!empty($developer_country_raw)) {
                            $developer_country_query = Country::where('name', $developer_country_raw)->where('is_deleted', 0)->first();
                            if ($developer_country_query) {
                                $developer_country = $developer_country_query->id;
                            } else {
                                $developer_country_query = new Country();
                                $developer_country_query->name = $developer_country_raw;
                                $developer_country_query->is_active = 1;
                                $developer_country_query->save();

                                $developer_country = $developer_country_query->id;
                            }
                        }
                    }
                    // 70. Phone No.
                    $developer_phone_no = '';
                    if (isset($row['70']) && !empty($row['70'])) {
                        $developer_phone_no = trim($row['70']);
                    }
                    // 71. Fax No.
                    $developer_fax_no = '';
                    if (isset($row['71']) && !empty($row['71'])) {
                        $developer_fax_no = trim($row['71']);
                    }
                    // 72. Remarks
                    $developer_remarks = '';
                    if (isset($row['72']) && !empty($row['72'])) {
                        $developer_remarks = trim($row['72']);
                    }

                    $new_developer = ManagementDeveloper::where('file_id', $files->id)->first();
                    $is_new_developer = empty($new_developer);
                    if ($is_new_developer) {
                        $new_developer = new ManagementDeveloper();
                        $new_developer->file_id = $files->id;
                    }
                    $new_developer->management_id = $management->id;
                    $this->applyExcelFields($new_developer, $row, $is_new_developer, [
                        'name' => ['col' => '61', 'val' => $developer_name],
                        'address_1' => ['col' => '62', 'val' => $developer_address1],
                        'address_2' => ['col' => '63', 'val' => $developer_address2],
                        'address_3' => ['col' => '64', 'val' => $developer_address3],
                        'address_4' => ['col' => '65', 'val' => $developer_address4],
                        'city' => ['col' => '67', 'val' => $developer_city],
                        'poscode' => ['col' => '66', 'val' => $developer_postcode],
                        'state' => ['col' => '68', 'val' => $developer_state],
                        'country' => ['col' => '69', 'val' => $developer_country],
                        'phone_no' => ['col' => '70', 'val' => $developer_phone_no],
                        'fax_no' => ['col' => '71', 'val' => $developer_fax_no],
                        'remarks' => ['col' => '72', 'val' => $developer_remarks],
                    ]);
                    $new_developer->save();
                }

                /** Create Liquidator */
                if ($management->liquidator) {
                    // 74. Name
                    $liquidator_name = '';
                    if (isset($row['74']) && !empty($row['74'])) {
                        $liquidator_name = trim($row['74']);
                    }
                    // 75. Address 1
                    $liquidator_address1 = '';
                    if (isset($row['75']) && !empty($row['75'])) {
                        $liquidator_address1 = trim($row['75']);
                    }
                    // 76. Address 2
                    $liquidator_address2 = '';
                    if (isset($row['76']) && !empty($row['76'])) {
                        $liquidator_address2 = trim($row['76']);
                    }
                    // 77. Address 3
                    $liquidator_address3 = '';
                    if (isset($row['77']) && !empty($row['77'])) {
                        $liquidator_address3 = trim($row['77']);
                    }
                    // 78. Address 4
                    $liquidator_address4 = '';
                    if (isset($row['78']) && !empty($row['78'])) {
                        $liquidator_address4 = trim($row['78']);
                    }
                    // 79. Postcode
                    $liquidator_postcode = '';
                    if (isset($row['79']) && !empty($row['79'])) {
                        $liquidator_postcode = trim($row['79']);
                    }
                    // 80. City
                    $liquidator_city = '';
                    if (isset($row['80']) && !empty($row['80'])) {
                        $liquidator_city_raw = trim($row['80']);

                        if (!empty($liquidator_city_raw)) {
                            $liquidator_city_query = City::where('description', $liquidator_city_raw)->where('is_deleted', 0)->first();
                            if ($liquidator_city_query) {
                                $liquidator_city = $liquidator_city_query->id;
                            } else {
                                $liquidator_city_query = new City();
                                $liquidator_city_query->description = $liquidator_city_raw;
                                $liquidator_city_query->is_active = 1;
                                $liquidator_city_query->save();

                                $liquidator_city = $liquidator_city_query->id;
                            }
                        }
                    }
                    // 81. State
                    $liquidator_state = '';
                    if (isset($row['81']) && !empty($row['81'])) {
                        $liquidator_state_raw = trim($row['81']);

                        if (!empty($liquidator_state_raw)) {
                            $liquidator_state_query = State::where('name', $liquidator_state_raw)->where('is_deleted', 0)->first();
                            if ($liquidator_state_query) {
                                $liquidator_state = $liquidator_state_query->id;
                            } else {
                                $liquidator_state_query = new State();
                                $liquidator_state_query->name = $liquidator_state_raw;
                                $liquidator_state_query->is_active = 1;
                                $liquidator_state_query->save();

                                $liquidator_state = $liquidator_state_query->id;
                            }
                        }
                    }
                    // 82. Country
                    $liquidator_country = '';
                    if (isset($row['82']) && !empty($row['82'])) {
                        $liquidator_country_raw = trim($row['82']);

                        if (!empty($liquidator_country_raw)) {
                            $liquidator_country_query = Country::where('name', $liquidator_country_raw)->where('is_deleted', 0)->first();
                            if ($liquidator_country_query) {
                                $liquidator_country = $liquidator_country_query->id;
                            } else {
                                $liquidator_country_query = new Country();
                                $liquidator_country_query->name = $liquidator_country_raw;
                                $liquidator_country_query->is_active = 1;
                                $liquidator_country_query->save();

                                $liquidator_country = $liquidator_country_query->id;
                            }
                        }
                    }
                    // 83. Phone No.
                    $liquidator_phone_no = '';
                    if (isset($row['83']) && !empty($row['83'])) {
                        $liquidator_phone_no = trim($row['83']);
                    }
                    // 84. Fax No.
                    $liquidator_fax_no = '';
                    if (isset($row['84']) && !empty($row['84'])) {
                        $liquidator_fax_no = trim($row['84']);
                    }
                    // 85. Remarks
                    $liquidator_remarks = '';
                    if (isset($row['85']) && !empty($row['85'])) {
                        $liquidator_remarks = trim($row['85']);
                    }

                    $new_liquidator = ManagementLiquidator::where('file_id', $files->id)->first();
                    $is_new_liquidator = empty($new_liquidator);
                    if ($is_new_liquidator) {
                        $new_liquidator = new ManagementLiquidator();
                        $new_liquidator->file_id = $files->id;
                    }
                    $new_liquidator->management_id = $management->id;
                    $this->applyExcelFields($new_liquidator, $row, $is_new_liquidator, [
                        'name' => ['col' => '74', 'val' => $liquidator_name],
                        'address_1' => ['col' => '75', 'val' => $liquidator_address1],
                        'address_2' => ['col' => '76', 'val' => $liquidator_address2],
                        'address_3' => ['col' => '77', 'val' => $liquidator_address3],
                        'address_4' => ['col' => '78', 'val' => $liquidator_address4],
                        'city' => ['col' => '80', 'val' => $liquidator_city],
                        'poscode' => ['col' => '79', 'val' => $liquidator_postcode],
                        'state' => ['col' => '81', 'val' => $liquidator_state],
                        'country' => ['col' => '82', 'val' => $liquidator_country],
                        'phone_no' => ['col' => '83', 'val' => $liquidator_phone_no],
                        'fax_no' => ['col' => '84', 'val' => $liquidator_fax_no],
                        'remarks' => ['col' => '85', 'val' => $liquidator_remarks],
                    ]);
                    $new_liquidator->save();
                }

                if ($management->is_jmb) {
                    // 87. Date Formed
                    $jmb_date_formed = '';
                    if (isset($row['87']) && !empty($row['87'])) {
                        if (!empty($row['61']['date'])) {
                            $jmb_date_formed = Carbon::parse($row['87']['date'])->format('Y-m-d');
                        } else {
                            $jmb_date_formed = Carbon::createFromFormat('d/m/Y', trim($row['87']))->format('Y-m-d');
                        }
                    }
                    // 88. Certificate Series No
                    $jmb_certificate_no = '';
                    if (isset($row['88']) && !empty($row['88'])) {
                        $jmb_certificate_no = trim($row['88']);
                    }
                    // 89. Name
                    $jmb_name = '';
                    if (isset($row['89']) && !empty($row['89'])) {
                        $jmb_name = trim($row['89']);
                    }
                    // 90. Address 1
                    $jmb_address1 = '';
                    if (isset($row['90']) && !empty($row['90'])) {
                        $jmb_address1 = trim($row['90']);
                    }
                    // 91. Address 2
                    $jmb_address2 = '';
                    if (isset($row['91']) && !empty($row['91'])) {
                        $jmb_address2 = trim($row['91']);
                    }
                    // 92. Address 3
                    $jmb_address3 = '';
                    if (isset($row['92']) && !empty($row['92'])) {
                        $jmb_address3 = trim($row['92']);
                    }
                    // 93. Address 4
                    $jmb_address4 = '';
                    if (isset($row['93']) && !empty($row['93'])) {
                        $jmb_address4 = trim($row['93']);
                    }
                    // 94. Postcode
                    $jmb_postcode = '';
                    if (isset($row['94']) && !empty($row['94'])) {
                        $jmb_postcode = trim($row['94']);
                    }
                    // 95. City
                    $jmb_city = '';
                    if (isset($row['95']) && !empty($row['95'])) {
                        $jmb_city_raw = trim($row['95']);

                        if (!empty($jmb_city_raw)) {
                            $jmb_city_query = City::where('description', $jmb_city_raw)->where('is_deleted', 0)->first();
                            if ($jmb_city_query) {
                                $jmb_city = $jmb_city_query->id;
                            } else {
                                $jmb_city_query = new City();
                                $jmb_city_query->description = $jmb_city_raw;
                                $jmb_city_query->is_active = 1;
                                $jmb_city_query->save();

                                $jmb_city = $jmb_city_query->id;
                            }
                        }
                    }
                    // 96. State
                    $jmb_state = '';
                    if (isset($row['96']) && !empty($row['96'])) {
                        $jmb_state_raw = trim($row['96']);

                        if (!empty($jmb_state_raw)) {
                            $jmb_state_query = State::where('name', $jmb_state_raw)->where('is_deleted', 0)->first();
                            if ($jmb_state_query) {
                                $jmb_state = $jmb_state_query->id;
                            } else {
                                $jmb_state_query = new State();
                                $jmb_state_query->name = $jmb_state_raw;
                                $jmb_state_query->is_active = 1;
                                $jmb_state_query->save();

                                $jmb_state = $jmb_state_query->id;
                            }
                        }
                    }
                    // 97. Country
                    $jmb_country = '';
                    if (isset($row['97']) && !empty($row['97'])) {
                        $jmb_country_raw = trim($row['97']);

                        if (!empty($jmb_country_raw)) {
                            $jmb_country_query = Country::where('name', $jmb_country_raw)->where('is_deleted', 0)->first();
                            if ($jmb_country_query) {
                                $jmb_country = $jmb_country_query->id;
                            } else {
                                $jmb_country_query = new Country();
                                $jmb_country_query->name = $jmb_country_raw;
                                $jmb_country_query->is_active = 1;
                                $jmb_country_query->save();

                                $jmb_country = $jmb_country_query->id;
                            }
                        }
                    }
                    // 98. Office No.
                    $jmb_office_no = '';
                    if (isset($row['98']) && !empty($row['98'])) {
                        $jmb_office_no = trim($row['98']);
                    }
                    // 99. Fax No.
                    $jmb_fax_no = '';
                    if (isset($row['99']) && !empty($row['99'])) {
                        $jmb_fax_no = trim($row['99']);
                    }
                    // 100. Email
                    $jmb_email = '';
                    if (isset($row['100']) && !empty($row['100'])) {
                        $jmb_email = trim($row['100']);
                    }

                    $new_jmb = ManagementJMB::where('file_id', $files->id)->first();
                    $is_new_jmb = empty($new_jmb);
                    if ($is_new_jmb) {
                        $new_jmb = new ManagementJMB();
                        $new_jmb->file_id = $files->id;
                    }
                    $new_jmb->management_id = $management->id;
                    $this->applyExcelFields($new_jmb, $row, $is_new_jmb, [
                        'date_formed' => ['col' => '87', 'val' => $jmb_date_formed],
                        'certificate_no' => ['col' => '88', 'val' => $jmb_certificate_no],
                        'name' => ['col' => '89', 'val' => $jmb_name],
                        'address1' => ['col' => '90', 'val' => $jmb_address1],
                        'address2' => ['col' => '91', 'val' => $jmb_address2],
                        'address3' => ['col' => '92', 'val' => $jmb_address3],
                        'address4' => ['col' => '93', 'val' => $jmb_address4],
                        'city' => ['col' => '95', 'val' => $jmb_city],
                        'poscode' => ['col' => '94', 'val' => $jmb_postcode],
                        'state' => ['col' => '96', 'val' => $jmb_state],
                        'country' => ['col' => '97', 'val' => $jmb_country],
                        'phone_no' => ['col' => '98', 'val' => $jmb_office_no],
                        'fax_no' => ['col' => '99', 'val' => $jmb_fax_no],
                        'email' => ['col' => '100', 'val' => $jmb_email],
                    ]);
                    $new_jmb->save();
                }

                if ($management->is_mc) {
                    // 102. Date Formed
                    $mc_date_formed = '';
                    if (isset($row['102']) && !empty($row['102'])) {
                        if (!empty($row['102']['date'])) {
                            $mc_date_formed = Carbon::parse($row['102']['date'])->format('Y-m-d');
                        } else {
                            $mc_date_formed = Carbon::createFromFormat('d/m/Y', trim($row['102']))->format('Y-m-d');
                        }
                    }
                    // 103. First AGM Date
                    $mc_first_agm = '';
                    if (isset($row['103']) && !empty($row['103'])) {
                        if (!empty($row['103']['date'])) {
                            $mc_first_agm = Carbon::parse($row['103']['date'])->format('Y-m-d');
                        } else {
                            $mc_first_agm = Carbon::createFromFormat('d/m/Y', trim($row['103']))->format('Y-m-d');
                        }
                    }
                    // 104. Name
                    $mc_name = '';
                    if (isset($row['104']) && !empty($row['104'])) {
                        $mc_name = trim($row['104']);
                    }
                    // 105. Address 1
                    $mc_address1 = '';
                    if (isset($row['105']) && !empty($row['105'])) {
                        $mc_address1 = trim($row['105']);
                    }
                    // 106. Address 2
                    $mc_address2 = '';
                    if (isset($row['106']) && !empty($row['106'])) {
                        $mc_address2 = trim($row['106']);
                    }
                    // 107. Address 3
                    $mc_address3 = '';
                    if (isset($row['107']) && !empty($row['107'])) {
                        $mc_address3 = trim($row['107']);
                    }
                    // 107. Address 4
                    $mc_address4 = '';
                    if (isset($row['108']) && !empty($row['108'])) {
                        $mc_address4 = trim($row['108']);
                    }
                    // 109. Postcode
                    $mc_postcode = '';
                    if (isset($row['109']) && !empty($row['109'])) {
                        $mc_postcode = trim($row['109']);
                    }
                    // 110. City
                    $mc_city = '';
                    if (isset($row['110']) && !empty($row['110'])) {
                        $mc_city_raw = trim($row['110']);

                        if (!empty($mc_city_raw)) {
                            $mc_city_query = City::where('description', $mc_city_raw)->where('is_deleted', 0)->first();
                            if ($mc_city_query) {
                                $mc_city = $mc_city_query->id;
                            } else {
                                $mc_city_query = new City();
                                $mc_city_query->description = $mc_city_raw;
                                $mc_city_query->is_active = 1;
                                $mc_city_query->save();

                                $mc_city = $mc_city_query->id;
                            }
                        }
                    }
                    // 111. State
                    $mc_state = '';
                    if (isset($row['111']) && !empty($row['111'])) {
                        $mc_state_raw = trim($row['111']);

                        if (!empty($mc_state_raw)) {
                            $mc_state_query = State::where('name', $mc_state_raw)->where('is_deleted', 0)->first();
                            if ($mc_state_query) {
                                $mc_state = $mc_state_query->id;
                            } else {
                                $mc_state_query = new State();
                                $mc_state_query->name = $mc_state_raw;
                                $mc_state_query->is_active = 1;
                                $mc_state_query->save();

                                $mc_state = $mc_state_query->id;
                            }
                        }
                    }
                    // 112. Country
                    $mc_country = '';
                    if (isset($row['112']) && !empty($row['112'])) {
                        $mc_country_raw = trim($row['112']);

                        if (!empty($mc_country_raw)) {
                            $mc_country_query = Country::where('name', $mc_country_raw)->where('is_deleted', 0)->first();
                            if ($mc_country_query) {
                                $mc_country = $mc_country_query->id;
                            } else {
                                $mc_country_query = new Country();
                                $mc_country_query->name = $mc_country_raw;
                                $mc_country_query->is_active = 1;
                                $mc_country_query->save();

                                $mc_country = $mc_country_query->id;
                            }
                        }
                    }
                    // 113. Office No.
                    $mc_office_no = '';
                    if (isset($row['113']) && !empty($row['113'])) {
                        $mc_office_no = trim($row['113']);
                    }
                    // 114. Fax No.
                    $mc_fax_no = '';
                    if (isset($row['114']) && !empty($row['114'])) {
                        $mc_fax_no = trim($row['114']);
                    }
                    // 115. Email
                    $mc_email = '';
                    if (isset($row['115']) && !empty($row['115'])) {
                        $mc_email = trim($row['115']);
                    }

                    // 150. Certificate No
                    $certificate_no = 0;
                    if (isset($row['150']) && !empty($row['150'])) {
                        $certificate_no = trim($row['150']);
                    }

                    $new_mc = ManagementMC::where('file_id', $files->id)->first();
                    $is_new_mc = empty($new_mc);
                    if ($is_new_mc) {
                        $new_mc = new ManagementMC();
                        $new_mc->file_id = $files->id;
                    }
                    $new_mc->management_id = $management->id;
                    $this->applyExcelFields($new_mc, $row, $is_new_mc, [
                        'date_formed' => ['col' => '102', 'val' => $mc_date_formed],
                        'certificate_no' => ['col' => '150', 'val' => $certificate_no],
                        'first_agm' => ['col' => '103', 'val' => $mc_first_agm],
                        'name' => ['col' => '104', 'val' => $mc_name],
                        'address1' => ['col' => '105', 'val' => $mc_address1],
                        'address2' => ['col' => '106', 'val' => $mc_address2],
                        'address3' => ['col' => '107', 'val' => $mc_address3],
                        'address4' => ['col' => '108', 'val' => $mc_address4],
                        'city' => ['col' => '110', 'val' => $mc_city],
                        'poscode' => ['col' => '109', 'val' => $mc_postcode],
                        'state' => ['col' => '111', 'val' => $mc_state],
                        'country' => ['col' => '112', 'val' => $mc_country],
                        'phone_no' => ['col' => '113', 'val' => $mc_office_no],
                        'fax_no' => ['col' => '114', 'val' => $mc_fax_no],
                        'email' => ['col' => '115', 'val' => $mc_email],
                    ]);
                    $new_mc->save();
                }

                if ($management->is_agent) {
                    // 117. Selected By
                    $agent_selected_by = '';
                    if (isset($row['117']) && !empty($row['117'])) {
                        $agent_selected_by = trim($row['117']);
                    }
                    // 118. Agent Name
                    $agent_name = '';
                    if (isset($row['118']) && !empty($row['118'])) {
                        $agent_name = trim($row['118']);
                    }
                    // 119. Address 1
                    $agent_address1 = '';
                    if (isset($row['119']) && !empty($row['119'])) {
                        $agent_address1 = trim($row['119']);
                    }
                    // 120. Address 2
                    $agent_address2 = '';
                    if (isset($row['120']) && !empty($row['120'])) {
                        $agent_address2 = trim($row['120']);
                    }
                    // 121. Address 3
                    $agent_address3 = '';
                    if (isset($row['121']) && !empty($row['121'])) {
                        $agent_address3 = trim($row['121']);
                    }
                    // 122. Address 4
                    $agent_address4 = '';
                    if (isset($row['122']) && !empty($row['122'])) {
                        $agent_address4 = trim($row['122']);
                    }
                    // 123. Postcode
                    $agent_postcode = '';
                    if (isset($row['123']) && !empty($row['123'])) {
                        $agent_postcode = trim($row['123']);
                    }
                    // 124. City
                    $agent_city = '';
                    if (isset($row['124']) && !empty($row['124'])) {
                        $agent_city_raw = trim($row['124']);

                        if (!empty($agent_city_raw)) {
                            $agent_city_query = City::where('description', $agent_city_raw)->where('is_deleted', 0)->first();
                            if ($agent_city_query) {
                                $agent_city = $agent_city_query->id;
                            } else {
                                $agent_city_query = new City();
                                $agent_city_query->description = $agent_city_raw;
                                $agent_city_query->is_active = 1;
                                $agent_city_query->save();

                                $agent_city = $agent_city_query->id;
                            }
                        }
                    }
                    // 125. State
                    $agent_state = '';
                    if (isset($row['125']) && !empty($row['125'])) {
                        $agent_state_raw = trim($row['125']);

                        if (!empty($agent_state_raw)) {
                            $agent_state_query = State::where('name', $agent_state_raw)->where('is_deleted', 0)->first();
                            if ($agent_state_query) {
                                $agent_state = $agent_state_query->id;
                            } else {
                                $agent_state_query = new State();
                                $agent_state_query->name = $agent_state_raw;
                                $agent_state_query->is_active = 1;
                                $agent_state_query->save();

                                $agent_state = $agent_state_query->id;
                            }
                        }
                    }
                    // 126. Country
                    $agent_country = '';
                    if (isset($row['126']) && !empty($row['126'])) {
                        $agent_country_raw = trim($row['126']);

                        if (!empty($agent_country_raw)) {
                            $agent_country_query = Country::where('name', $agent_country_raw)->where('is_deleted', 0)->first();
                            if ($agent_country_query) {
                                $agent_country = $agent_country_query->id;
                            } else {
                                $agent_country_query = new Country();
                                $agent_country_query->name = $agent_country_raw;
                                $agent_country_query->is_active = 1;
                                $agent_country_query->save();

                                $agent_country = $agent_country_query->id;
                            }
                        }
                    }
                    // 127. Office No.
                    $agent_office_no = '';
                    if (isset($row['127']) && !empty($row['127'])) {
                        $agent_office_no = trim($row['127']);
                    }
                    // 128. Fax No.
                    $agent_fax_no = '';
                    if (isset($row['128']) && !empty($row['128'])) {
                        $agent_fax_no = trim($row['128']);
                    }
                    // 129. Email
                    $agent_email = '';
                    if (isset($row['129']) && !empty($row['129'])) {
                        $agent_email = trim($row['129']);
                    }

                    $new_agent = ManagementAgent::where('file_id', $files->id)->first();
                    $is_new_agent = empty($new_agent);
                    if ($is_new_agent) {
                        $new_agent = new ManagementAgent();
                        $new_agent->file_id = $files->id;
                    }
                    $new_agent->management_id = $management->id;
                    $this->applyExcelFields($new_agent, $row, $is_new_agent, [
                        'selected_by' => ['col' => '117', 'val' => $agent_selected_by],
                        'agent' => ['col' => '118', 'val' => $agent_name],
                        'address1' => ['col' => '119', 'val' => $agent_address1],
                        'address2' => ['col' => '120', 'val' => $agent_address2],
                        'address3' => ['col' => '121', 'val' => $agent_address3],
                        'address4' => ['col' => '122', 'val' => $agent_address4],
                        'city' => ['col' => '124', 'val' => $agent_city],
                        'poscode' => ['col' => '123', 'val' => $agent_postcode],
                        'state' => ['col' => '125', 'val' => $agent_state],
                        'country' => ['col' => '126', 'val' => $agent_country],
                        'phone_no' => ['col' => '127', 'val' => $agent_office_no],
                        'fax_no' => ['col' => '128', 'val' => $agent_fax_no],
                        'email' => ['col' => '129', 'val' => $agent_email],
                    ]);
                    $new_agent->save();
                }

                if ($management->is_others) {
                    // 131. Name
                    $others_name = '';
                    if (isset($row['131']) && !empty($row['131'])) {
                        $others_name = trim($row['131']);
                    }
                    // 132. Address 1
                    $others_address1 = '';
                    if (isset($row['132']) && !empty($row['132'])) {
                        $others_address1 = trim($row['132']);
                    }
                    // 133. Address 2
                    $others_address2 = '';
                    if (isset($row['133']) && !empty($row['133'])) {
                        $others_address2 = trim($row['133']);
                    }
                    // 134. Address 3
                    $others_address3 = '';
                    if (isset($row['134']) && !empty($row['134'])) {
                        $others_address3 = trim($row['134']);
                    }
                    // 135. Address 4
                    $others_address4 = '';
                    if (isset($row['135']) && !empty($row['135'])) {
                        $others_address4 = trim($row['135']);
                    }
                    // 136. Postcode
                    $others_postcode = '';
                    if (isset($row['136']) && !empty($row['136'])) {
                        $others_postcode = trim($row['136']);
                    }
                    // 137. City
                    $others_city = '';
                    if (isset($row['137']) && !empty($row['137'])) {
                        $others_city_raw = trim($row['137']);

                        if (!empty($others_city_raw)) {
                            $others_city_query = City::where('description', $others_city_raw)->where('is_deleted', 0)->first();
                            if ($others_city_query) {
                                $others_city = $others_city_query->id;
                            } else {
                                $others_city_query = new City();
                                $others_city_query->description = $others_city_raw;
                                $others_city_query->is_active = 1;
                                $others_city_query->save();

                                $others_city = $others_city_query->id;
                            }
                        }
                    }
                    // 138. State
                    $others_state = '';
                    if (isset($row['138']) && !empty($row['138'])) {
                        $others_state_raw = trim($row['138']);

                        if (!empty($others_state_raw)) {
                            $others_state_query = State::where('name', $others_state_raw)->where('is_deleted', 0)->first();
                            if ($others_state_query) {
                                $others_state = $others_state_query->id;
                            } else {
                                $others_state_query = new State();
                                $others_state_query->name = $others_state_raw;
                                $others_state_query->is_active = 1;
                                $others_state_query->save();

                                $others_state = $others_state_query->id;
                            }
                        }
                    }
                    // 139. Country
                    $others_country = '';
                    if (isset($row['139']) && !empty($row['139'])) {
                        $others_country_raw = trim($row['139']);

                        if (!empty($others_country_raw)) {
                            $others_country_query = Country::where('name', $others_country_raw)->where('is_deleted', 0)->first();
                            if ($others_country_query) {
                                $others_country = $others_country_query->id;
                            } else {
                                $others_country_query = new Country();
                                $others_country_query->name = $others_country_raw;
                                $others_country_query->is_active = 1;
                                $others_country_query->save();

                                $others_country = $others_country_query->id;
                            }
                        }
                    }
                    // 140. Office No.
                    $others_office_no = '';
                    if (isset($row['140']) && !empty($row['140'])) {
                        $others_office_no = trim($row['140']);
                    }
                    // 141. Fax No.
                    $others_fax_no = '';
                    if (isset($row['141']) && !empty($row['141'])) {
                        $others_fax_no = trim($row['141']);
                    }
                    // 142. Email
                    $others_email = '';
                    if (isset($row['142']) && !empty($row['142'])) {
                        $others_email = trim($row['142']);
                    }

                    $new_others = ManagementOthers::where('file_id', $files->id)->first();
                    $is_new_management_others = empty($new_others);
                    if ($is_new_management_others) {
                        $new_others = new ManagementOthers();
                        $new_others->file_id = $files->id;
                    }
                    $new_others->management_id = $management->id;
                    $this->applyExcelFields($new_others, $row, $is_new_management_others, [
                        'name' => ['col' => '131', 'val' => $others_name],
                        'address1' => ['col' => '132', 'val' => $others_address1],
                        'address2' => ['col' => '133', 'val' => $others_address2],
                        'address3' => ['col' => '134', 'val' => $others_address3],
                        'address4' => ['col' => '135', 'val' => $others_address4],
                        'city' => ['col' => '137', 'val' => $others_city],
                        'poscode' => ['col' => '136', 'val' => $others_postcode],
                        'state' => ['col' => '138', 'val' => $others_state],
                        'country' => ['col' => '139', 'val' => $others_country],
                        'phone_no' => ['col' => '140', 'val' => $others_office_no],
                        'fax_no' => ['col' => '141', 'val' => $others_fax_no],
                        'email' => ['col' => '142', 'val' => $others_email],
                    ]);
                    $new_others->save();
                }
            }

            // 148. Precalculate Plan
            $precalculate_plan = '';
            if (isset($row['148']) && !empty($row['148'])) {
                $precalculate_plan = trim($row['148']);
            }
            // 149. Buyer Registration
            $buyer_registration = '';
            if (isset($row['149']) && !empty($row['149'])) {
                $buyer_registration = trim($row['149']);
            }
            // 150. Certificate No
            $certificate_no = '';
            if (isset($row['150']) && !empty($row['150'])) {
                $certificate_no = trim($row['150']);
            }
            // 151. Financial Report Start Month

            $monitor = Monitoring::where('file_id', $files->id)->first();
            $is_new_monitor = empty($monitor);
            if ($is_new_monitor) {
                $monitor = new Monitoring();
                $monitor->file_id = $files->id;
            }
            $this->applyExcelFields($monitor, $row, $is_new_monitor, [
                'pre_calculate' => ['col' => '148', 'val' => $precalculate_plan],
                'buyer_registration' => ['col' => '149', 'val' => $buyer_registration],
                'certificate_no' => ['col' => '150', 'val' => $certificate_no],
            ]);
            $monitor->save();

            // 152. Name
            $other_details_name = '';
            if (isset($row['152']) && !empty($row['152'])) {
                $other_details_name = trim($row['152']);
            }
            // 153. Latitude
            $latitude = '';
            if (isset($row['153']) && !empty($row['153'])) {
                $latitude = trim($row['153']);
            }
            // 154. Longitude
            $longitude = '';
            if (isset($row['154']) && !empty($row['154'])) {
                $longitude = trim($row['154']);
            }

            $others_details = OtherDetails::where('file_id', $files->id)->first();
            $is_new_other_details = empty($others_details);
            if ($is_new_other_details) {
                $others_details = new OtherDetails();
                $others_details->file_id = $files->id;
            }
            $this->applyExcelFields($others_details, $row, $is_new_other_details, [
                'name' => ['col' => '152', 'val' => $other_details_name],
                'latitude' => ['col' => '153', 'val' => $latitude],
                'longitude' => ['col' => '154', 'val' => $longitude],
            ]);
            if ($is_new_other_details) {
                $others_details->image_url = '';
                $others_details->description = '';
                $others_details->pms_system = '';
                $others_details->owner_occupied = '';
                $others_details->rented = '';
                $others_details->bantuan_lphs = '';
                $others_details->bantuan_others = '';
                $others_details->rsku = '';
                $others_details->water_meter = '';
                $others_details->tnb = '';
                $others_details->malay_composition = '';
                $others_details->chinese_composition = '';
                $others_details->indian_composition = '';
                $others_details->others_composition = '';
                $others_details->foreigner_composition = '';
            }
            $others_details->save();
        }

        # Audit Trail
        $remarks = $files->file_no . $audit_message;
        $auditTrail = new AuditTrail();
        $auditTrail->module = "COB File";
        $auditTrail->remarks = $remarks;
        $auditTrail->audit_by = $user_id;
        $auditTrail->save();

        $job->delete();
    }

    private function hasExcelValue($row, $column)
    {
        if (!isset($row[$column])) {
            return false;
        }

        $value = $row[$column];

        if ($value === '' || $value === null) {
            return false;
        }

        if (is_array($value)) {
            return !empty($value['date']);
        }

        return trim((string) $value) !== '';
    }

    private function applyExcelFields($model, $row, $isNew, array $fields)
    {
        foreach ($fields as $attribute => $config) {
            if ($isNew || $this->hasExcelValue($row, $config['col'])) {
                $model->$attribute = $config['val'];
            }
        }
    }
}
