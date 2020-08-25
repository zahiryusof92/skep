<?php

class ImportController extends BaseController {

    public function showView($name) {
        if (View::exists($name)) {
            return View::make($name);
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );
            return View::make('404_en', $viewData);
        }
    }

    public function importBuyer() {
        if (Request::ajax()) {
            $excel = Input::file('import_file');
            $file_id = Input::get('file_id');

            if ($excel && $file_id) {

                $file = Files::find($file_id);

                if ($file) {

                    $path = $excel->getRealPath();
                    $data = Excel::load($path, function($reader) {
                                
                            })->get();

                    if (!empty($data) && $data->count()) {
                        foreach ($data->toArray() as $row) {
                            if (!empty($row)) {
                                // 1. File No.
                                $file_no = '';
                                if (isset($row['1']) && !empty($row['1'])) {
                                    $file_no = trim($row['1']);
                                }

                                if (!empty($file_no)) {
                                    $exist_file = Files::where('file_no', $file_no)->where('id', $file->id)->first();
                                    if ($exist_file) {
                                        // 2. Unit No.
                                        $unit_no = '';
                                        if (isset($row['2']) && !empty($row['2'])) {
                                            $unit_no = trim($row['2']);
                                        }

                                        if (!empty($unit_no)) {
                                            $check_buyer = Buyer::where('file_id', $file->id)->where('unit_no', $unit_no)->where('is_deleted', 0)->first();
                                            if (!$check_buyer) {
                                                $race = '';
                                                if (isset($row['17']) && !empty($row['17'])) {
                                                    $race_raw = trim($row['17']);

                                                    if (!empty($race_raw)) {
                                                        $race_query = Race::where('name', ucwords(strtolower($race_raw)))->where('is_deleted', 0)->first();
                                                        if ($race_query) {
                                                            $race = $race_query->id;
                                                        } else {
                                                            $race_query = new Race();
                                                            $race_query->name = ucwords(strtolower($race_raw));
                                                            $race_query->is_active = 1;
                                                            $race_query->save();

                                                            $race = $race_query->id;
                                                        }
                                                    }
                                                }

                                                $nationality = '';
                                                if (isset($row['18']) && !empty($row['18'])) {
                                                    $nationality_raw = trim($row['18']);

                                                    if (!empty($nationality_raw)) {
                                                        $nationality_query = Nationality::where('name', ucwords(strtolower($nationality_raw)))->where('is_deleted', 0)->first();
                                                        if ($nationality_query) {
                                                            $nationality = $nationality_query->id;
                                                        } else {
                                                            $nationality_query = new Nationality();
                                                            $nationality_query->name = ucwords(strtolower($nationality_raw));
                                                            $nationality_query->is_active = 1;
                                                            $nationality_query->save();

                                                            $nationality = $nationality_query->id;
                                                        }
                                                    }
                                                }

                                                $buyer = new Buyer();
                                                $buyer->file_id = $file->id;
                                                $buyer->unit_no = $unit_no;
                                                $buyer->no_petak = $row['3'];
                                                $buyer->no_petak_aksesori = $row['4'];
                                                $buyer->keluasan_lantai_petak = $row['5'];
                                                $buyer->keluasan_lantai_petak_aksesori = $row['6'];
                                                $buyer->unit_share = $row['7'];
                                                $buyer->jenis_kegunaan = $row['8'];
                                                $buyer->owner_name = $row['9'];
                                                $buyer->ic_company_no = $row['10'];
                                                $buyer->nama2 = $row['11'];
                                                $buyer->ic_no2 = $row['12'];
                                                $buyer->address = $row['13'];
                                                $buyer->alamat_surat_menyurat = $row['14'];
                                                $buyer->phone_no = $row['15'];                                                
                                                $buyer->email = $row['16'];
                                                $buyer->race_id = $race;
                                                $buyer->nationality_id = $nationality;
                                                $buyer->caj_penyelenggaraan = $row['20'];
                                                $buyer->sinking_fund = $row['21'];
                                                $buyer->remarks = $row['22'];
                                                $buyer->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        # Audit Trail
                        $remarks = 'COB Buyer List (' . $file->file_no . ') has been inserted.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

                        print "true";
                    } else {
                        print "empty_data";
                    }
                } else {
                    print "false";
                }
            } else {
                print "empty_file";
            }
        } else {
            print "false";
        }
    }
    
    public function importTenant() {
        if (Request::ajax()) {
            $excel = Input::file('import_file');
            $file_id = Input::get('file_id');

            if ($excel && $file_id) {

                $file = Files::find($file_id);

                if ($file) {

                    $path = $excel->getRealPath();
                    $data = Excel::load($path, function($reader) {
                                
                            })->get();

                    if (!empty($data) && $data->count()) {
                        foreach ($data->toArray() as $row) {
                            if (!empty($row)) {
                                // 1. File No.
                                $file_no = '';
                                if (isset($row['1']) && !empty($row['1'])) {
                                    $file_no = trim($row['1']);
                                }

                                if (!empty($file_no)) {
                                    $exist_file = Files::where('file_no', $file_no)->where('id', $file->id)->first();
                                    if ($exist_file) {
                                        // 2. Unit No.
                                        $unit_no = '';
                                        if (isset($row['2']) && !empty($row['2'])) {
                                            $unit_no = trim($row['2']);
                                        }

                                        if (!empty($unit_no)) {
                                            $check_tenant = Tenant::where('file_id', $file->id)->where('unit_no', $unit_no)->where('is_deleted', 0)->first();
                                            if (!$check_tenant) {
                                                $race = '';
                                                if (isset($row['17']) && !empty($row['17'])) {
                                                    $race_raw = trim($row['17']);

                                                    if (!empty($race_raw)) {
                                                        $race_query = Race::where('name', ucwords(strtolower($race_raw)))->where('is_deleted', 0)->first();
                                                        if ($race_query) {
                                                            $race = $race_query->id;
                                                        } else {
                                                            $race_query = new Race();
                                                            $race_query->name = ucwords(strtolower($race_raw));
                                                            $race_query->is_active = 1;
                                                            $race_query->save();

                                                            $race = $race_query->id;
                                                        }
                                                    }
                                                }

                                                $nationality = '';
                                                if (isset($row['18']) && !empty($row['18'])) {
                                                    $nationality_raw = trim($row['18']);

                                                    if (!empty($nationality_raw)) {
                                                        $nationality_query = Nationality::where('name', ucwords(strtolower($nationality_raw)))->where('is_deleted', 0)->first();
                                                        if ($nationality_query) {
                                                            $nationality = $nationality_query->id;
                                                        } else {
                                                            $nationality_query = new Nationality();
                                                            $nationality_query->name = ucwords(strtolower($nationality_raw));
                                                            $nationality_query->is_active = 1;
                                                            $nationality_query->save();

                                                            $nationality = $nationality_query->id;
                                                        }
                                                    }
                                                }

                                                $tenant = new Tenant();
                                                $tenant->file_id = $file->id;
                                                $tenant->unit_no = $unit_no;
                                                $tenant->no_petak = $row['3'];
                                                $tenant->no_petak_aksesori = $row['4'];
                                                $tenant->keluasan_lantai_petak = $row['5'];
                                                $tenant->keluasan_lantai_petak_aksesori = $row['6'];
                                                $tenant->unit_share = $row['7'];
                                                $tenant->jenis_kegunaan = $row['8'];
                                                $tenant->tenant_name = $row['9'];
                                                $tenant->ic_company_no = $row['10'];
                                                $tenant->nama2 = $row['11'];
                                                $tenant->ic_no2 = $row['12'];
                                                $tenant->address = $row['13'];
                                                $tenant->alamat_surat_menyurat = $row['14'];
                                                $tenant->phone_no = $row['15'];                                                
                                                $tenant->email = $row['16'];
                                                $tenant->race_id = $race;
                                                $tenant->nationality_id = $nationality;
                                                $tenant->caj_penyelenggaraan = $row['20'];
                                                $tenant->sinking_fund = $row['21'];
                                                $tenant->remarks = $row['22'];
                                                $tenant->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        # Audit Trail
                        $remarks = 'COB Tenant List (' . $file->file_no . ') has been inserted.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

                        print "true";
                    } else {
                        print "empty_data";
                    }
                } else {
                    print "false";
                }
            } else {
                print "empty_file";
            }
        } else {
            print "false";
        }
    }

    public function importCOBFile() {
        if (Request::ajax()) {
            $file = Input::file('import_file');
            $company_id = Input::get('import_company');
            $status = Input::get('status');

            if ($file) {

                $path = $file->getRealPath();
                $data = Excel::load($path, function($reader) {
                            
                        })->get();

                if (!empty($data) && $data->count()) {
                    foreach ($data->toArray() as $row) {
                        if (!empty($row)) {
                            // 1. File No.
                            $file_no = '';
                            if (isset($row['1']) && !empty($row['1'])) {
                                $file_no = trim($row['1']);
                            }

                            if (!empty($file_no)) {
                                $exist_file = Files::where('company_id', $company_id)->where('file_no', $file_no)->where('is_deleted', 0)->first();
                                if (!$exist_file) {
                                    // 3. Year
                                    $year = '';
                                    if (isset($row['3']) && !empty($row['3'])) {
                                        $year = trim($row['3']);
                                    }
                                    // 124. Status
                                    $is_active = 0;
                                    if (isset($row['124']) && !empty($row['124'])) {
                                        $is_active_raw = trim($row['124']);

                                        if (!empty($is_active_raw)) {
                                            if (strtolower($is_active_raw) == 'aktif' || strtolower($is_active_raw) == 'active') {
                                                $is_active = 1;
                                            }
                                        }
                                    }

                                    $files = new Files();
                                    $files->company_id = $company_id;
                                    $files->file_no = $file_no;
                                    $files->year = $year;
                                    $files->is_active = $is_active;
                                    $files->status = $status;
                                    if ($status == 1 || $status == '1') {
                                        $files->approved_by = Auth::user()->id;
                                        $files->approved_at = date('Y-m-d H:i:s');
                                    }
                                    $files->created_by = Auth::user()->id;
                                    $create_file = $files->save();

                                    if ($create_file) {
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

                                        $house_scheme = new HouseScheme();
                                        $house_scheme->file_id = $files->id;
                                        $house_scheme->name = $name;
                                        $house_scheme->developer = $developer;
                                        $house_scheme->address1 = $address1;
                                        $house_scheme->address2 = $address2;
                                        $house_scheme->address3 = $address3;
                                        $house_scheme->address4 = $address4;
                                        $house_scheme->poscode = $postcode;
                                        $house_scheme->city = $city;
                                        $house_scheme->state = $state;
                                        $house_scheme->country = $country;
                                        $house_scheme->phone_no = $phone_no;
                                        $house_scheme->fax_no = $fax_no;
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
                                            $vacant_date = trim($row['40']);
                                        }
                                        // 41. Date CCC
                                        $ccc_date = '';
                                        if (isset($row['41']) && !empty($row['41'])) {
                                            $ccc_date = trim($row['41']);
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

                                        $strata = new Strata();
                                        $strata->file_id = $files->id;
                                        $strata->title = $strata_title;
                                        $strata->name = $strata_name;
                                        $strata->parliament = $parliament;
                                        $strata->dun = $dun;
                                        $strata->park = $park;
                                        $strata->address1 = $strata_address1;
                                        $strata->address2 = $strata_address2;
                                        $strata->address3 = $strata_address3;
                                        $strata->address4 = $strata_address4;
                                        $strata->poscode = $strata_postcode;
                                        $strata->city = $strata_city;
                                        $strata->state = $strata_state;
                                        $strata->country = $strata_country;
                                        $strata->block_no = $block_no;
                                        $strata->total_floor = $total_floor;
                                        $strata->year = $strata_year;
                                        $strata->town = $town;
                                        $strata->area = $area;
                                        $strata->land_area = $land_area;
                                        $strata->total_share_unit = $total_share_unit;
                                        $strata->land_area_unit = $land_area_unit;
                                        $strata->lot_no = $lot_no;
                                        $strata->ownership_no = $ownership_no;
                                        $strata->date = $vacant_date;
                                        $strata->land_title = $land_title;
                                        $strata->category = $category;
                                        $strata->perimeter = $perimeter;
                                        $strata->ccc_no = $ccc_no;
                                        $strata->ccc_date = $ccc_date;
                                        $strata->file_url = '';
                                        $strata->is_residential = $is_residential;
                                        $strata->is_commercial = $is_commercial;
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

                                                $residential = new Residential();
                                                $residential->file_id = $files->id;
                                                $residential->strata_id = $strata->id;
                                                $residential->unit_no = $residential_unit_no;
                                                $residential->maintenance_fee = $residential_mf;
                                                $residential->maintenance_fee_option = $residential_mf_unit;
                                                $residential->sinking_fund = $residential_sf;
                                                $residential->sinking_fund_option = $residential_sf_unit;
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

                                                $commercial = new Commercial();
                                                $commercial->file_id = $files->id;
                                                $commercial->strata_id = $strata->id;
                                                $commercial->unit_no = $commercial_unit_no;
                                                $commercial->maintenance_fee = $commercial_mf;
                                                $commercial->maintenance_fee_option = $commercial_mf_unit;
                                                $commercial->sinking_fund = $commercial_sf;
                                                $commercial->sinking_fund_option = $commercial_sf_unit;
                                                $commercial->save();
                                            }

                                            $facility = new Facility();
                                            $facility->file_id = $files->id;
                                            $facility->strata_id = $strata->id;
                                            $facility->save();
                                        }

                                        // 60. JMB
                                        $is_jmb = 0;
                                        if (isset($row['60']) && !empty($row['60'])) {
                                            $is_jmb_raw = trim($row['60']);

                                            if (strtolower($is_jmb_raw) == 'yes') {
                                                $is_jmb = 1;
                                            }
                                        }

                                        // 75. MC
                                        $is_mc = 0;
                                        if (isset($row['75']) && !empty($row['75'])) {
                                            $is_mc_raw = trim($row['75']);

                                            if (strtolower($is_mc_raw) == 'yes') {
                                                $is_mc = 1;
                                            }
                                        }

                                        // 90. Agent
                                        $is_agent = 0;
                                        if (isset($row['90']) && !empty($row['90'])) {
                                            $is_agent_raw = trim($row['90']);

                                            if (strtolower($is_agent_raw) == 'yes') {
                                                $is_agent = 1;
                                            }
                                        }

                                        // 104. Others
                                        $is_others = 0;
                                        if (isset($row['104']) && !empty($row['104'])) {
                                            $is_others_raw = trim($row['104']);

                                            if (strtolower($is_others_raw) == 'yes') {
                                                $is_others = 1;
                                            }
                                        }

                                        $management = new Management();
                                        $management->file_id = $files->id;
                                        $management->is_jmb = $is_jmb;
                                        $management->is_mc = $is_mc;
                                        $management->is_agent = $is_agent;
                                        $management->is_others = $is_others;
                                        $create_management = $management->save();

                                        if ($create_management) {
                                            if ($is_jmb) {
                                                // 61. Date Formed
                                                $jmb_date_formed = '';
                                                if (isset($row['61']) && !empty($row['61'])) {
                                                    $jmb_date_formed = trim($row['61']);
                                                }
                                                // 62. Certificate Series No
                                                $jmb_certificate_no = '';
                                                if (isset($row['62']) && !empty($row['62'])) {
                                                    $jmb_certificate_no = trim($row['62']);
                                                }
                                                // 63. Name
                                                $jmb_name = '';
                                                if (isset($row['63']) && !empty($row['63'])) {
                                                    $jmb_name = trim($row['63']);
                                                }
                                                // 64. Address 1
                                                $jmb_address1 = '';
                                                if (isset($row['64']) && !empty($row['64'])) {
                                                    $jmb_address1 = trim($row['64']);
                                                }
                                                // 65. Address 2
                                                $jmb_address2 = '';
                                                if (isset($row['65']) && !empty($row['65'])) {
                                                    $jmb_address2 = trim($row['65']);
                                                }
                                                // 66. Address 3
                                                $jmb_address3 = '';
                                                if (isset($row['66']) && !empty($row['66'])) {
                                                    $jmb_address3 = trim($row['66']);
                                                }
                                                // 67. Address 4
                                                $jmb_address4 = '';
                                                if (isset($row['67']) && !empty($row['67'])) {
                                                    $jmb_address4 = trim($row['67']);
                                                }
                                                // 68. Postcode
                                                $jmb_postcode = '';
                                                if (isset($row['68']) && !empty($row['68'])) {
                                                    $jmb_postcode = trim($row['68']);
                                                }
                                                // 69. City
                                                $jmb_city = '';
                                                if (isset($row['69']) && !empty($row['69'])) {
                                                    $jmb_city_raw = trim($row['69']);

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
                                                // 70. State
                                                $jmb_state = '';
                                                if (isset($row['70']) && !empty($row['70'])) {
                                                    $jmb_state_raw = trim($row['70']);

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
                                                // 71. Country
                                                $jmb_country = '';
                                                if (isset($row['71']) && !empty($row['71'])) {
                                                    $jmb_country_raw = trim($row['71']);

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
                                                // 72. Office No.
                                                $jmb_office_no = '';
                                                if (isset($row['72']) && !empty($row['72'])) {
                                                    $jmb_office_no = trim($row['72']);
                                                }
                                                // 73. Fax No.
                                                $jmb_fax_no = '';
                                                if (isset($row['73']) && !empty($row['73'])) {
                                                    $jmb_fax_no = trim($row['73']);
                                                }
                                                // 74. Email
                                                $jmb_email = '';
                                                if (isset($row['74']) && !empty($row['74'])) {
                                                    $jmb_email = trim($row['74']);
                                                }

                                                $new_jmb = new ManagementJMB();
                                                $new_jmb->file_id = $files->id;
                                                $new_jmb->management_id = $management->id;
                                                $new_jmb->date_formed = $jmb_date_formed;
                                                $new_jmb->certificate_no = $jmb_certificate_no;
                                                $new_jmb->name = $jmb_name;
                                                $new_jmb->address1 = $jmb_address1;
                                                $new_jmb->address2 = $jmb_address2;
                                                $new_jmb->address3 = $jmb_address3;
                                                $new_jmb->address4 = $jmb_address4;
                                                $new_jmb->city = $jmb_city;
                                                $new_jmb->poscode = $jmb_postcode;
                                                $new_jmb->state = $jmb_state;
                                                $new_jmb->country = $jmb_country;
                                                $new_jmb->phone_no = $jmb_office_no;
                                                $new_jmb->fax_no = $jmb_fax_no;
                                                $new_jmb->email = $jmb_email;
                                                $new_jmb->save();
                                            }

                                            if ($is_mc) {
                                                // 76. Date Formed
                                                $mc_date_formed = '';
                                                if (isset($row['76']) && !empty($row['76'])) {
                                                    $mc_date_formed = trim($row['76']);
                                                }
                                                // 77. First AGM Date
                                                $mc_first_agm = '';
                                                if (isset($row['77']) && !empty($row['77'])) {
                                                    $mc_first_agm = trim($row['77']);
                                                }
                                                // 78. Name
                                                $mc_name = '';
                                                if (isset($row['78']) && !empty($row['78'])) {
                                                    $mc_name = trim($row['78']);
                                                }
                                                // 79. Address 1
                                                $mc_address1 = '';
                                                if (isset($row['79']) && !empty($row['79'])) {
                                                    $mc_address1 = trim($row['79']);
                                                }
                                                // 80. Address 2
                                                $mc_address2 = '';
                                                if (isset($row['80']) && !empty($row['80'])) {
                                                    $mc_address2 = trim($row['80']);
                                                }
                                                // 81. Address 3
                                                $mc_address3 = '';
                                                if (isset($row['81']) && !empty($row['81'])) {
                                                    $mc_address3 = trim($row['81']);
                                                }
                                                // 82. Address 4
                                                $mc_address4 = '';
                                                if (isset($row['82']) && !empty($row['82'])) {
                                                    $mc_address4 = trim($row['82']);
                                                }
                                                // 83. Postcode
                                                $mc_postcode = '';
                                                if (isset($row['83']) && !empty($row['83'])) {
                                                    $mc_postcode = trim($row['83']);
                                                }
                                                // 84. City
                                                $mc_city = '';
                                                if (isset($row['84']) && !empty($row['84'])) {
                                                    $mc_city_raw = trim($row['84']);

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
                                                // 85. State
                                                $mc_state = '';
                                                if (isset($row['85']) && !empty($row['85'])) {
                                                    $mc_state_raw = trim($row['85']);

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
                                                // 86. Country
                                                $mc_country = '';
                                                if (isset($row['86']) && !empty($row['86'])) {
                                                    $mc_country_raw = trim($row['86']);

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
                                                // 87. Office No.
                                                $mc_office_no = '';
                                                if (isset($row['87']) && !empty($row['87'])) {
                                                    $mc_office_no = trim($row['87']);
                                                }
                                                // 88. Fax No.
                                                $mc_fax_no = '';
                                                if (isset($row['88']) && !empty($row['88'])) {
                                                    $mc_fax_no = trim($row['88']);
                                                }
                                                // 89. Email
                                                $mc_email = '';
                                                if (isset($row['89']) && !empty($row['89'])) {
                                                    $mc_email = trim($row['89']);
                                                }

                                                // 125. Certificate No
                                                $certificate_no = 0;
                                                if (isset($row['125']) && !empty($row['125'])) {
                                                    $certificate_no = trim($row['125']);
                                                }

                                                $new_mc = new ManagementMC();
                                                $new_mc->file_id = $files->id;
                                                $new_mc->management_id = $management->id;
                                                $new_mc->date_formed = $mc_date_formed;
                                                $new_mc->certificate_no = $certificate_no;
                                                $new_mc->first_agm = $mc_first_agm;
                                                $new_mc->name = $mc_name;
                                                $new_mc->address1 = $mc_address1;
                                                $new_mc->address2 = $mc_address2;
                                                $new_mc->address3 = $mc_address3;
                                                $new_mc->address4 = $mc_address4;
                                                $new_mc->city = $mc_city;
                                                $new_mc->poscode = $mc_postcode;
                                                $new_mc->state = $mc_state;
                                                $new_mc->country = $mc_country;
                                                $new_mc->phone_no = $mc_office_no;
                                                $new_mc->fax_no = $mc_fax_no;
                                                $new_mc->email = $mc_email;
                                                $new_mc->save();
                                            }

                                            if ($is_agent) {
                                                // 91. Selected By
                                                $agent_selected_by = '';
                                                if (isset($row['91']) && !empty($row['91'])) {
                                                    $agent_selected_by = trim($row['91']);
                                                }
                                                // 92. Agent Name
                                                $agent_name = '';
                                                if (isset($row['92']) && !empty($row['92'])) {
                                                    $agent_name = trim($row['92']);
                                                }
                                                // 93. Address 1
                                                $agent_address1 = '';
                                                if (isset($row['93']) && !empty($row['93'])) {
                                                    $agent_address1 = trim($row['93']);
                                                }
                                                // 94. Address 2
                                                $agent_address2 = '';
                                                if (isset($row['94']) && !empty($row['94'])) {
                                                    $agent_address2 = trim($row['94']);
                                                }
                                                // 95. Address 3
                                                $agent_address3 = '';
                                                if (isset($row['95']) && !empty($row['95'])) {
                                                    $agent_address3 = trim($row['95']);
                                                }
                                                // 96. Address 4
                                                $agent_address4 = '';
                                                if (isset($row['96']) && !empty($row['96'])) {
                                                    $agent_address4 = trim($row['96']);
                                                }
                                                // 97. Postcode
                                                $agent_postcode = '';
                                                if (isset($row['97']) && !empty($row['97'])) {
                                                    $agent_postcode = trim($row['97']);
                                                }
                                                // 98. City
                                                $agent_city = '';
                                                if (isset($row['98']) && !empty($row['98'])) {
                                                    $agent_city_raw = trim($row['98']);

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
                                                // 99. State
                                                $agent_state = '';
                                                if (isset($row['99']) && !empty($row['99'])) {
                                                    $agent_state_raw = trim($row['99']);

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
                                                // 100. Country
                                                $agent_country = '';
                                                if (isset($row['100']) && !empty($row['100'])) {
                                                    $agent_country_raw = trim($row['100']);

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
                                                // 101. Office No.
                                                $agent_office_no = '';
                                                if (isset($row['101']) && !empty($row['101'])) {
                                                    $agent_office_no = trim($row['101']);
                                                }
                                                // 102. Fax No.
                                                $agent_fax_no = '';
                                                if (isset($row['102']) && !empty($row['102'])) {
                                                    $agent_fax_no = trim($row['102']);
                                                }
                                                // 103. Email
                                                $agent_email = '';
                                                if (isset($row['103']) && !empty($row['103'])) {
                                                    $agent_email = trim($row['103']);
                                                }

                                                $new_agent = new ManagementAgent();
                                                $new_agent->file_id = $files->id;
                                                $new_agent->management_id = $management->id;
                                                $new_agent->selected_by = $agent_selected_by;
                                                $new_agent->agent = $agent_name;
                                                $new_agent->address1 = $agent_address1;
                                                $new_agent->address2 = $agent_address2;
                                                $new_agent->address3 = $agent_address3;
                                                $new_agent->address4 = $agent_address4;
                                                $new_agent->city = $agent_city;
                                                $new_agent->poscode = $agent_postcode;
                                                $new_agent->state = $agent_state;
                                                $new_agent->country = $agent_country;
                                                $new_agent->phone_no = $agent_office_no;
                                                $new_agent->fax_no = $agent_fax_no;
                                                $new_agent->email = $agent_email;
                                                $new_agent->save();
                                            }

                                            if ($is_others) {
                                                // 105. Name
                                                $others_name = '';
                                                if (isset($row['105']) && !empty($row['105'])) {
                                                    $others_name = trim($row['105']);
                                                }
                                                // 106. Address 1
                                                $others_address1 = '';
                                                if (isset($row['106']) && !empty($row['106'])) {
                                                    $others_address1 = trim($row['106']);
                                                }
                                                // 107. Address 2
                                                $others_address2 = '';
                                                if (isset($row['107']) && !empty($row['107'])) {
                                                    $others_address2 = trim($row['107']);
                                                }
                                                // 108. Address 3
                                                $others_address3 = '';
                                                if (isset($row['108']) && !empty($row['108'])) {
                                                    $others_address3 = trim($row['108']);
                                                }
                                                // 109. Address 4
                                                $others_address4 = '';
                                                if (isset($row['109']) && !empty($row['109'])) {
                                                    $others_address4 = trim($row['109']);
                                                }
                                                // 110. Postcode
                                                $others_postcode = '';
                                                if (isset($row['110']) && !empty($row['110'])) {
                                                    $others_postcode = trim($row['110']);
                                                }
                                                // 111. City
                                                $others_city = '';
                                                if (isset($row['111']) && !empty($row['111'])) {
                                                    $others_city_raw = trim($row['111']);

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
                                                // 112. State
                                                $others_state = '';
                                                if (isset($row['112']) && !empty($row['112'])) {
                                                    $others_state_raw = trim($row['112']);

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
                                                // 113. Country
                                                $others_country = '';
                                                if (isset($row['113']) && !empty($row['113'])) {
                                                    $others_country_raw = trim($row['113']);

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
                                                // 114. Office No.
                                                $others_office_no = '';
                                                if (isset($row['114']) && !empty($row['114'])) {
                                                    $others_office_no = trim($row['114']);
                                                }
                                                // 115. Fax No.
                                                $others_fax_no = '';
                                                if (isset($row['115']) && !empty($row['115'])) {
                                                    $others_fax_no = trim($row['115']);
                                                }
                                                // 116. Email
                                                $others_email = '';
                                                if (isset($row['116']) && !empty($row['116'])) {
                                                    $others_email = trim($row['116']);
                                                }

                                                $new_others = new ManagementOthers();
                                                $new_others->file_id = $files->id;
                                                $new_others->management_id = $management->id;
                                                $new_others->name = $others_name;
                                                $new_others->address1 = $others_address1;
                                                $new_others->address2 = $others_address2;
                                                $new_others->address3 = $others_address3;
                                                $new_others->address4 = $others_address4;
                                                $new_others->city = $others_city;
                                                $new_others->poscode = $others_postcode;
                                                $new_others->state = $others_state;
                                                $new_others->country = $others_country;
                                                $new_others->phone_no = $others_office_no;
                                                $new_others->fax_no = $others_fax_no;
                                                $new_others->email = $others_email;
                                                $new_others->save();
                                            }
                                        }

                                        // 117. Precalculate Plan
                                        $precalculate_plan = '';
                                        if (isset($row['117']) && !empty($row['117'])) {
                                            $precalculate_plan = trim($row['117']);
                                        }
                                        // 118. Buyer Registration
                                        $buyer_registration = '';
                                        if (isset($row['118']) && !empty($row['118'])) {
                                            $buyer_registration = trim($row['118']);
                                        }
                                        // 119. Certificate No
                                        $certificate_no = '';
                                        if (isset($row['119']) && !empty($row['119'])) {
                                            $certificate_no = trim($row['119']);
                                        }
                                        // 120. Financial Report Start Month

                                        $monitor = new Monitoring();
                                        $monitor->file_id = $files->id;
                                        $monitor->pre_calculate = $precalculate_plan;
                                        $monitor->buyer_registration = $buyer_registration;
                                        $monitor->certificate_no = $certificate_no;
                                        $monitor->save();

                                        // 121. Name
                                        $other_details_name = '';
                                        if (isset($row['121']) && !empty($row['121'])) {
                                            $other_details_name = trim($row['121']);
                                        }
                                        // 122. Latitude
                                        $latitude = '';
                                        if (isset($row['122']) && !empty($row['122'])) {
                                            $latitude = trim($row['122']);
                                        }
                                        // 123. Longitude
                                        $longitude = '';
                                        if (isset($row['123']) && !empty($row['123'])) {
                                            $longitude = trim($row['123']);
                                        }

                                        $others_details = new OtherDetails();
                                        $others_details->file_id = $files->id;
                                        $others_details->name = $other_details_name;
                                        $others_details->image_url = '';
                                        $others_details->latitude = $latitude;
                                        $others_details->longitude = $longitude;
                                        $others_details->description = '';
                                        $others_details->pms_system = '';
                                        $others_details->owner_occupied = '';
                                        $others_details->rented = '';
                                        $others_details->bantuan_lphs = '';
                                        $others_details->bantuan_others = '';
                                        $others_details->rsku = '';
                                        $others_details->water_meter = '';
                                        $others_details->malay_composition = '';
                                        $others_details->chinese_composition = '';
                                        $others_details->indian_composition = '';
                                        $others_details->others_composition = '';
                                        $others_details->foreigner_composition = '';
                                        $others_details->save();

                                        # Audit Trail
                                        $remarks = $files->file_no . ' has been imported.';
                                        $auditTrail = new AuditTrail();
                                        $auditTrail->module = "COB File";
                                        $auditTrail->remarks = $remarks;
                                        $auditTrail->audit_by = Auth::user()->id;
                                        $auditTrail->save();
                                    }
                                } else {
                                    // 125. New File No
                                    $new_file_no = '';
                                    if (isset($row['125']) && !empty($row['125'])) {
                                        $new_file_no = trim($row['125']);
                                    }

                                    if (!empty($new_file_no)) {
                                        $exist_file->file_no = $new_file_no;
                                        $update_file = $exist_file->save();

                                        if ($update_file) {
                                            # Audit Trail
                                            $remarks = $exist_file->file_no . ' has been updated.';
                                            $auditTrail = new AuditTrail();
                                            $auditTrail->module = "COB File";
                                            $auditTrail->remarks = $remarks;
                                            $auditTrail->audit_by = Auth::user()->id;
                                            $auditTrail->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($create_file) || !empty($update_file)) {
                        print 'true';
                    } else {
                        print 'empty_data';
                    }
                } else {
                    print "empty_data";
                }
            } else {
                print "empty_file";
            }
        } else {
            print "false";
        }
    }

}
