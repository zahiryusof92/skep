<?php

use Carbon\Carbon;

class LPHSController extends BaseController {

    function randomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function getYear() {
        $min_year = Files::where('year', '>', 0)->min('year');
        $max_year = date('Y');

        $year = [];
        for ($max_year; $max_year >= $min_year; $max_year--) {
            $year[] = (int) $max_year;
        }

        return $year;
    }

    public function council($cob) {
        if (!empty($cob) && $cob != 'all') {
            $councils = Company::where('short_name', $cob)->where('is_main', 0)->where('is_deleted', 0)->orderBy('short_name')->get();
        } else {
            $councils = Company::where('is_main', 0)->where('is_deleted', 0)->orderBy('short_name')->get();
        }

        return $councils;
    }

    public function result($result, $filename, $output = 'excel') {
        if ($output == 'excel') {
            Excel::create($filename, function ($excel) use ($filename, $result) {
                $excel->sheet($filename, function ($sheet) use ($result) {
                    $sheet->fromArray($result);
                });
            })->export('xlsx');
        }

        return '<pre>' . json_encode($result, JSON_PRETTY_PRINT) . '</pre>';
    }

    public function jmbFiles($cob = null) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    $file_lists = $council->files;

                    foreach ($file_lists as $files) {
                        if (!$files->jmb) {

                            $council_name = $council->short_name;
                            $file_no = $files->file_no;
                            $file_name = $files->strata->name;
                            $username = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $files->file_no));
                            $password = $this->randomString();
                            $full_name = strtoupper(preg_replace('/[^\p{L}\p{N}\s]/u', '', $files->file_no));
                            $email = '';
                            $phone_no = '';
                            $role = Role::where('name', Role::JMB)->pluck('id');
                            $start_date = Carbon::now()->format('Y-m-d');
                            $end_date = Carbon::now()->addMonth(2)->format('Y-m-d');
                            $file_id = $files->id;
                            $company_id = $council->id;
                            $developer_id = null;
                            $remarks = 'Created by System';
                            $is_active = 1;
                            $is_deleted = 0;
                            $status = 1;
                            $approved_by = Auth::user()->id;
                            $approved_at = Carbon::now()->format('Y-m-d H:i:s');

                            $user = new User();
                            $user->username = $username;
                            $user->password = Hash::make($password);
                            $user->full_name = $full_name;
                            $user->email = $email;
                            $user->phone_no = $phone_no;
                            $user->role = $role;
                            $user->start_date = $start_date;
                            $user->end_date = $end_date;
                            $user->file_id = $file_id;
                            $user->company_id = $company_id;
                            $user->developer_id = $developer_id;
                            $user->remarks = $remarks;
                            $user->is_active = $is_active;
                            $user->is_deleted = $is_deleted;
                            $user->status = $status;
                            $user->approved_by = $approved_by;
                            $user->approved_at = $approved_at;
                            $success = $user->save();

                            if ($success) {
                                $result[$files->id] = [
                                    trans('app.menus.reporting.council') => $council_name,
                                    trans('app.forms.file_no') => $file_no,
                                    trans('app.forms.file_name') => $file_name,
                                    trans('app.forms.username') => $username,
                                    trans('app.forms.password') => $password,
                                    trans('app.forms.date_start') => $start_date,
                                    trans('app.forms.date_end') => $end_date
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'JMB_' . $council->short_name);
    }

    public function finance($cob = null, $year = null) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils && $year) {
            foreach ($councils as $council) {
                /*
                 * Finance
                 */
                $total_finance = DB::table('finance_file')
                        ->leftJoin('files', 'finance_file.file_id', '=', 'files.id')
                        ->where('finance_file.year', $year)
                        ->where('files.company_id', $council->id)
                        ->where('finance_file.company_id', $council->id)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0)
                        ->count();

                for ($month = 1; $month <= 12; $month++) {
                    $finance = DB::table('finance_file')
                            ->join('files', 'finance_file.file_id', '=', 'files.id')
                            ->where('finance_file.month', $month)
                            ->where('finance_file.year', $year)
                            ->where('files.company_id', $council->id)
                            ->where('finance_file.company_id', $council->id)
                            ->where('files.is_deleted', 0)
                            ->where('finance_file.is_deleted', 0)
                            ->count();

                    $financeList[$month] = $finance;
                }


                $total_file = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_file,
                    trans('Total Finance') => $total_finance,
                    trans('Year') => $year,
                    trans('Jan') => $financeList[1],
                    trans('Feb') => $financeList[2],
                    trans('Mar') => $financeList[3],
                    trans('Apr') => $financeList[4],
                    trans('May') => $financeList[5],
                    trans('Jun') => $financeList[6],
                    trans('Jul') => $financeList[7],
                    trans('Aug') => $financeList[8],
                    trans('Sep') => $financeList[9],
                    trans('Oct') => $financeList[10],
                    trans('Nov') => $financeList[11],
                    trans('Dec') => $financeList[12],
                ];
            }
        }

        return $this->result($result, $filename = 'Finance_' . $year);
    }

    public function developer($cob = null) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_developer = 0;
                $name = 0;
                $phone_no = 0;
                $fax_no = 0;
                $address = 0;
                $city = 0;
                $poscode = 0;
                $state = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Developer
                         */
                        $developer = DB::table('developer')
                                ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                                ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                ->select('developer.*')
                                ->where('files.id', $files->id)
                                ->first();

                        if (!empty($developer)) {
                            $total_developer = $total_developer + count($developer);

                            if (empty($developer->name) || $developer->name == null) {
                                $name++;
                            }
                            if (empty($developer->phone_no) || $developer->phone_no == null) {
                                $phone_no++;
                            }
                            if (empty($developer->fax_no) || $developer->fax_no == null) {
                                $fax_no++;
                            }
                            if (empty($developer->address1) || $developer->address1 == null) {
                                $address++;
                            }
                            if (empty($developer->city) || $developer->city == null) {
                                $city++;
                            }
                            if (empty($developer->poscode) || $developer->poscode == null) {
                                $poscode++;
                            }
                            if (empty($developer->state) || $developer->state == 0) {
                                $state++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total Developer') => $total_developer,
                    trans('app.forms.name') => ($total_developer - $name),
                    trans('app.forms.phone_number') => ($total_developer - $phone_no),
                    trans('app.forms.fax_number') => ($total_developer - $fax_no),
                    trans('app.forms.address') => ($total_developer - $address),
                    trans('app.forms.city') => ($total_developer - $city),
                    trans('app.forms.postcode') => ($total_developer - $poscode),
                    trans('app.forms.state') => ($total_developer - $state),
                ];
            }
        }

        return $this->result($result, $filename = 'Developer');
    }

    public function strata($cob = null) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $name = 0;
                $parliament = 0;
                $dun = 0;
                $park = 0;
                $address = 0;
                $city = 0;
                $poscode = 0;
                $state = 0;
                $block_no = 0;
                $total_floor = 0;
                $year = 0;
                $ownership_no = 0;
                $town = 0;
                $area = 0;
                $land_area = 0;
                $lot_no = 0;
                $date = 0;
                $land_title = 0;
                $category = 0;
                $perimeter = 0;
                $file_url = 0;
                $total_share_unit = 0;
                $ccc_no = 0;
                $ccc_date = 0;
                $residential_commercial = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Strata
                         */
                        if ($files->strata) {
                            if (empty($files->strata->name) || $files->strata->name == null) {
                                $name++;
                            }
                            if (empty($files->strata->parliament) || $files->strata->parliament == 0) {
                                $parliament++;
                            }
                            if (empty($files->strata->dun) || $files->strata->dun == 0) {
                                $dun++;
                            }
                            if (empty($files->strata->park) || $files->strata->park == 0) {
                                $park++;
                            }
                            if (empty($files->strata->address1) || $files->strata->address1 == null) {
                                $address++;
                            }
                            if (empty($files->strata->city) || $files->strata->city == null) {
                                $city++;
                            }
                            if (empty($files->strata->poscode) || $files->strata->poscode == null) {
                                $poscode++;
                            }
                            if (empty($files->strata->state) || $files->strata->state == 0) {
                                $state++;
                            }
                            if (empty($files->strata->block_no) || $files->strata->block_no == null) {
                                $block_no++;
                            }
                            if (empty($files->strata->total_floor) || $files->strata->total_floor == null) {
                                $total_floor++;
                            }
                            if (empty($files->strata->year) || $files->strata->year <= 0) {
                                $year++;
                            }
                            if (empty($files->strata->ownership_no) || $files->strata->ownership_no == null) {
                                $ownership_no++;
                            }
                            if (empty($files->strata->town) || $files->strata->town == 0) {
                                $town++;
                            }
                            if (empty($files->strata->area) || $files->strata->area == 0) {
                                $area++;
                            }
                            if (empty($files->strata->land_area) || $files->strata->land_area <= 0) {
                                $land_area++;
                            }
                            if (empty($files->strata->lot_no) || $files->strata->lot_no == null) {
                                $lot_no++;
                            }
                            if (empty($files->strata->date) || $files->strata->date <= 0) {
                                $date++;
                            }
                            if (empty($files->strata->land_title) || $files->strata->land_title == 0) {
                                $land_title++;
                            }
                            if (empty($files->strata->category) || $files->strata->category == 0) {
                                $category++;
                            }
                            if (empty($files->strata->perimeter) || $files->strata->perimeter == 0) {
                                $perimeter++;
                            }
                            if (empty($files->strata->file_url) || $files->strata->file_url == null) {
                                $file_url++;
                            }
                            if (empty($files->strata->total_share_unit) || $files->strata->total_share_unit <= 0) {
                                $total_share_unit++;
                            }
                            if (empty($files->strata->ccc_no) || $files->strata->ccc_no == null) {
                                $ccc_no++;
                            }
                            if (empty($files->strata->ccc_date) || $files->strata->ccc_date <= 0) {
                                $ccc_date++;
                            }
                            if ((empty($files->strata->is_residential) || $files->strata->is_residential == 0) && (empty($files->strata->is_commercial) || $files->strata->is_commercial == 0)) {
                                $residential_commercial++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('app.forms.name') => ($total_council - $name),
                    trans('app.forms.parliament') => ($total_council - $parliament),
                    trans('app.forms.dun') => ($total_council - $dun),
                    trans('app.forms.park') => ($total_council - $park),
                    trans('app.forms.address') => ($total_council - $address),
                    trans('app.forms.city') => ($total_council - $city),
                    trans('app.forms.postcode') => ($total_council - $poscode),
                    trans('app.forms.state') => ($total_council - $state),
                    trans('app.forms.number_of_block') => ($total_council - $block_no),
                    trans('app.forms.floor') => ($total_council - $total_floor),
                    trans('app.forms.year') => ($total_council - $year),
                    trans('app.forms.ownership_number') => ($total_council - $ownership_no),
                    trans('app.forms.city_town_district') => ($total_council - $town),
                    trans('app.forms.area') => ($total_council - $area),
                    trans('app.forms.total_land_area') => ($total_council - $land_area),
                    trans('app.forms.lot_number') => ($total_council - $lot_no),
                    trans('app.forms.date_vp') => ($total_council - $date),
                    trans('app.forms.land_title') => ($total_council - $land_title),
                    trans('app.forms.category') => ($total_council - $category),
                    trans('app.forms.perimeter') => ($total_council - $perimeter),
                    trans('app.forms.land_title') => ($total_council - $file_url),
                    trans('app.forms.total_share_unit') => ($total_council - $total_share_unit),
                    trans('app.forms.ccc_no') => ($total_council - $ccc_no),
                    trans('app.forms.date_ccc') => ($total_council - $ccc_date),
                    trans('app.forms.residential_block') . ' or ' . trans('app.forms.commercial_block') => ($total_council - $residential_commercial),
                ];
            }
        }

        return $this->result($result, $filename = 'Strata');
    }

    public function management($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                /*
                 * Management
                 */
                $mc = 0;
                $jmb = 0;
                $agent = 0;
                $others = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * JMB
                             */
                            if ($files->management->is_jmb) {
                                if ($files->management->is_mc && $files->managementMC) {
                                    
                                } else if ($files->managementJMB) {
                                    $jmb++;
                                }
                            }

                            /*
                             * MC
                             */
                            if ($files->management->is_mc) {
                                if ($files->managementMC) {
                                    $mc++;
                                }
                            }

                            /*
                             * Agent
                             */
                            if ($files->management->is_agent) {
                                if ($files->managementAgent) {
                                    $agent++;
                                }
                            }

                            /*
                             * Others
                             */
                            if ($files->management->is_others) {
                                if ($files->managementOthers) {
                                    $others++;
                                }
                            }
                        }
                    }
                }
                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total JMB') => $jmb,
                    trans('Total MC') => $mc,
                    trans('Total Agent') => $agent,
                    trans('Total Others') => $others
                ];
            }
        }

        return $this->result($result, $filename = 'Management');
    }

    public function jmb($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                /*
                 * Management
                 */
                $jmb = 0;

                /*
                 * JMB
                 */
                $jmb_name = 0;
                $jmb_date_formed = 0;
                $jmb_certificate_no = 0;
                $jmb_address = 0;
                $jmb_city = 0;
                $jmb_poscode = 0;
                $jmb_state = 0;
                $jmb_phone_no = 0;
                $jmb_fax_no = 0;
                $jmb_email = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * MC
                             */
                            if ($files->management->is_mc) {
                                if ($files->managementMC) {
                                    continue;
                                }
                            }

                            /*
                             * JMB
                             */
                            if ($files->management->is_jmb) {
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->name) || $files->managementJMB->name == null) {
                                        $jmb_name++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->date_formed) || $files->managementJMB->date_formed <= 0) {
                                        $jmb_date_formed++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->certificate_no) || $files->managementJMB->certificate_no == null) {
                                        $jmb_certificate_no++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->address1) || $files->managementJMB->address1 == null) {
                                        $jmb_address++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->city) || $files->managementJMB->city == 0) {
                                        $jmb_city++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->poscode) || $files->managementJMB->poscode == null) {
                                        $jmb_poscode++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->state) || $files->managementJMB->state == 0) {
                                        $jmb_state++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->phone_no) || $files->managementJMB->phone_no == null) {
                                        $jmb_phone_no++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->fax_no) || $files->managementJMB->fax_no == null) {
                                        $jmb_fax_no++;
                                    }
                                }
                                if ($files->managementJMB) {
                                    if (empty($files->managementJMB->email) || $files->managementJMB->email == null) {
                                        $jmb_email++;
                                    }
                                }

                                $jmb++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total JMB') => $jmb,
                    trans('app.forms.name') => ($jmb - $jmb_name),
                    trans('app.forms.date_formed') => ($jmb - $jmb_date_formed),
                    trans('app.forms.certificate_series_number') => ($jmb - $jmb_certificate_no),
                    trans('app.forms.address') => ($jmb - $jmb_address),
                    trans('app.forms.city') => ($jmb - $jmb_city),
                    trans('app.forms.postcode') => ($jmb - $jmb_poscode),
                    trans('app.forms.state') => ($jmb - $jmb_state),
                    trans('app.forms.phone_number') => ($jmb - $jmb_phone_no),
                    trans('app.forms.fax_number') => ($jmb - $jmb_fax_no),
                    trans('app.forms.email') => ($jmb - $jmb_email),
                ];
            }
        }

        return $this->result($result, $filename = 'JMB');
    }

    public function mc($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                /*
                 * Management
                 */
                $mc = 0;

                /*
                 * MC
                 */
                $mc_name = 0;
                $mc_date_formed = 0;
                $mc_certificate_no = 0;
                $mc_first_agm = 0;
                $mc_address = 0;
                $mc_city = 0;
                $mc_poscode = 0;
                $mc_state = 0;
                $mc_phone_no = 0;
                $mc_fax_no = 0;
                $mc_email = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * MC
                             */
                            if ($files->management->is_mc) {
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->name) || $files->managementMC->name == null) {
                                        $mc_name++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->date_formed) || $files->managementMC->date_formed <= 0) {
                                        $mc_date_formed++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->certificate_no) || $files->managementMC->certificate_no == null) {
                                        $mc_certificate_no++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->first_agm) || $files->managementMC->first_agm <= 0) {
                                        $mc_first_agm++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->address1) || $files->managementMC->address1 == null) {
                                        $mc_address++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->city) || $files->managementMC->city == 0) {
                                        $mc_city++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->poscode) || $files->managementMC->poscode == null) {
                                        $mc_poscode++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->state) || $files->managementMC->state == 0) {
                                        $mc_state++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->phone_no) || $files->managementMC->phone_no == null) {
                                        $mc_phone_no++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->fax_no) || $files->managementMC->fax_no == null) {
                                        $mc_fax_no++;
                                    }
                                }
                                if ($files->managementMC) {
                                    if (empty($files->managementMC->email) || $files->managementMC->email == null) {
                                        $mc_email++;
                                    }
                                }

                                $mc++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total MC') => $mc,
                    trans('app.forms.name') => ($mc - $mc_name),
                    trans('app.forms.date_formed') => ($mc - $mc_date_formed),
                    trans('app.forms.certificate_series_number') => ($mc - $mc_certificate_no),
                    trans('app.forms.first_agm_date') => ($mc - $mc_first_agm),
                    trans('app.forms.address') => ($mc - $mc_address),
                    trans('app.forms.city') => ($mc - $mc_city),
                    trans('app.forms.postcode') => ($mc - $mc_poscode),
                    trans('app.forms.state') => ($mc - $mc_state),
                    trans('app.forms.phone_number') => ($mc - $mc_phone_no),
                    trans('app.forms.fax_number') => ($mc - $mc_fax_no),
                    trans('app.forms.city') => ($mc - $mc_email),
                ];
            }
        }

        return $this->result($result, $filename = 'MC');
    }

    public function agent($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                /*
                 * Management
                 */
                $agent = 0;

                /*
                 * Agent
                 */
                $agent_name = 0;
                $agent_selected_by = 0;
                $agent_address = 0;
                $agent_city = 0;
                $agent_poscode = 0;
                $agent_state = 0;
                $agent_phone_no = 0;
                $agent_fax_no = 0;
                $agent_email = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * Agent
                             */
                            if ($files->management->is_agent) {
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->agent) || $files->managementAgent->agent == null) {
                                        $agent_name++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->selected_by) || $files->managementAgent->selected_by == null) {
                                        $agent_selected_by++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->address1) || $files->managementAgent->address1 == null) {
                                        $agent_address++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->city) || $files->managementAgent->city == 0) {
                                        $agent_city++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->poscode) || $files->managementAgent->poscode == null) {
                                        $agent_poscode++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->state) || $files->managementAgent->state == 0) {
                                        $agent_state++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->phone_no) || $files->managementAgent->phone_no == null) {
                                        $agent_phone_no++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->fax_no) || $files->managementAgent->fax_no == null) {
                                        $agent_fax_no++;
                                    }
                                }
                                if ($files->managementAgent) {
                                    if (empty($files->managementAgent->email) || $files->managementAgent->email == null) {
                                        $agent_email++;
                                    }
                                }

                                $agent++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total Agent') => $agent,
                    trans('app.forms.name') => ($agent - $agent_name),
                    trans('app.forms.appoed_by') => ($agent - $agent_selected_by),
                    trans('app.forms.address') => ($agent - $agent_address),
                    trans('app.forms.city') => ($agent - $agent_city),
                    trans('app.forms.postcode') => ($agent - $agent_poscode),
                    trans('app.forms.state') => ($agent - $agent_state),
                    trans('app.forms.phone_number') => ($agent - $agent_phone_no),
                    trans('app.forms.fax_number') => ($agent - $agent_fax_no),
                    trans('app.forms.email') => ($agent - $agent_email),
                ];
            }
        }

        return $this->result($result, $filename = 'Agent');
    }

    public function others($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                /*
                 * Management
                 */
                $others = 0;

                /*
                 * Others
                 */
                $others_name = 0;
                $others_address = 0;
                $others_city = 0;
                $others_poscode = 0;
                $others_state = 0;
                $others_phone_no = 0;
                $others_fax_no = 0;
                $others_email = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * Others
                             */
                            if ($files->management->is_others) {
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->name) || $files->managementOthers->name == null) {
                                        $others_name++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->address1) || $files->managementOthers->address1 == null) {
                                        $others_address++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->city) || $files->managementOthers->city == 0) {
                                        $others_city++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->poscode) || $files->managementOthers->poscode == null) {
                                        $others_poscode++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->state) || $files->managementOthers->state == 0) {
                                        $others_state++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->phone_no) || $files->managementOthers->phone_no == null) {
                                        $others_phone_no++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->fax_no) || $files->managementOthers->fax_no == null) {
                                        $others_fax_no++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->email) || $files->managementOthers->email == null) {
                                        $others_email++;
                                    }
                                }

                                $others++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total Others') => $others,
                    trans('app.forms.name') => ($others - $others_name),
                    trans('app.forms.address') => ($others - $others_address),
                    trans('app.forms.city') => ($others - $others_city),
                    trans('app.forms.postcode') => ($others - $others_poscode),
                    trans('app.forms.state') => ($others - $others_state),
                    trans('app.forms.phone_number') => ($others - $others_phone_no),
                    trans('app.forms.fax_number') => ($others - $others_fax_no),
                    trans('app.forms.email') => ($others - $others_email),
                ];
            }
        }

        return $this->result($result, $filename = 'Others');
    }

    public function agm($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_agm = 0;
                $agm_date = 0;
                $agm = 0;
                $agm_file_url = 0;
                $egm = 0;
                $egm_file_url = 0;
                $minit_meeting = 0;
                $minutes_meeting_file_url = 0;
                $jmc_spa = 0;
                $jmc_file_url = 0;
                $identity_card = 0;
                $ic_file_url = 0;
                $attendance = 0;
                $attendance_file_url = 0;
                $financial_report = 0;
                $audited_financial_file_url = 0;
                $audit_report = 0;
                $audit_report_url = 0;
                $letter_egrity_url = 0;
                $letter_bankruptcy_url = 0;
                $notice_agm_egm_url = 0;
                $minutes_agm_egm_url = 0;
                $minutes_ajk_url = 0;
                $eligible_vote_url = 0;
                $attend_meeting_url = 0;
                $proksi_url = 0;
                $ajk_info_url = 0;
                $ic_url = 0;
                $purchase_aggrement_url = 0;
                $strata_title_url = 0;
                $maenance_statement_url = 0;
                $egrity_pledge_url = 0;
                $report_audited_financial_url = 0;
                $house_rules_url = 0;
                $audit_start_date = 0;
                $audit_end_date = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->meetingDocument) {
                            foreach ($files->meetingDocument as $meetingDocument) {
                                if (empty($meetingDocument->agm_date) || $meetingDocument->agm_date <= 0) {
                                    $agm_date++;
                                }
                                if (empty($meetingDocument->agm) || $meetingDocument->agm == 0) {
                                    $agm++;
                                }
                                if (empty($meetingDocument->agm_file_url) || $meetingDocument->agm_file_url == null) {
                                    $agm_file_url++;
                                }
                                if (empty($meetingDocument->egm) || $meetingDocument->egm <= 0) {
                                    $egm++;
                                }
                                if (empty($meetingDocument->egm_file_url) || $meetingDocument->egm_file_url == null) {
                                    $egm_file_url++;
                                }
                                if (empty($meetingDocument->minit_meeting) || $meetingDocument->minit_meeting == 0) {
                                    $minit_meeting++;
                                }
                                if (empty($meetingDocument->minutes_meeting_file_url) || $meetingDocument->minutes_meeting_file_url == null) {
                                    $minutes_meeting_file_url++;
                                }
                                if (empty($meetingDocument->jmc_spa) || $meetingDocument->jmc_spa == 0) {
                                    $jmc_spa++;
                                }
                                if (empty($meetingDocument->jmc_file_url) || $meetingDocument->jmc_file_url == null) {
                                    $jmc_file_url++;
                                }
                                if (empty($meetingDocument->identity_card) || $meetingDocument->identity_card == 0) {
                                    $identity_card++;
                                }
                                if (empty($meetingDocument->ic_file_url) || $meetingDocument->ic_file_url == null) {
                                    $ic_file_url++;
                                }
                                if (empty($meetingDocument->attendance) || $meetingDocument->attendance == 0) {
                                    $attendance++;
                                }
                                if (empty($meetingDocument->attendance_file_url) || $meetingDocument->attendance_file_url == null) {
                                    $attendance_file_url++;
                                }
                                if (empty($meetingDocument->financial_report) || $meetingDocument->attendance == 0) {
                                    $financial_report++;
                                }
                                if (empty($meetingDocument->audited_financial_file_url) || $meetingDocument->audited_financial_file_url == null) {
                                    $audited_financial_file_url++;
                                }
                                if (empty($meetingDocument->audit_report) || $meetingDocument->audit_report == null) {
                                    $audit_report++;
                                }
                                if (empty($meetingDocument->audit_report_url) || $meetingDocument->audit_report_url == null) {
                                    $audit_report_url++;
                                }
                                if (empty($meetingDocument->letter_egrity_url) || $meetingDocument->letter_egrity_url == null) {
                                    $letter_egrity_url++;
                                }
                                if (empty($meetingDocument->letter_bankruptcy_url) || $meetingDocument->letter_bankruptcy_url == null) {
                                    $letter_bankruptcy_url++;
                                }
                                if (empty($meetingDocument->notice_agm_egm_url) || $meetingDocument->notice_agm_egm_url == null) {
                                    $notice_agm_egm_url++;
                                }
                                if (empty($meetingDocument->minutes_agm_egm_url) || $meetingDocument->minutes_agm_egm_url == null) {
                                    $minutes_agm_egm_url++;
                                }
                                if (empty($meetingDocument->minutes_ajk_url) || $meetingDocument->minutes_ajk_url == null) {
                                    $minutes_ajk_url++;
                                }
                                if (empty($meetingDocument->eligible_vote_url) || $meetingDocument->eligible_vote_url == null) {
                                    $eligible_vote_url++;
                                }
                                if (empty($meetingDocument->attend_meeting_url) || $meetingDocument->attend_meeting_url == null) {
                                    $attend_meeting_url++;
                                }
                                if (empty($meetingDocument->proksi_url) || $meetingDocument->proksi_url == null) {
                                    $proksi_url++;
                                }
                                if (empty($meetingDocument->ajk_info_url) || $meetingDocument->ajk_info_url == null) {
                                    $ajk_info_url++;
                                }
                                if (empty($meetingDocument->ic_url) || $meetingDocument->ic_url == null) {
                                    $ic_url++;
                                }
                                if (empty($meetingDocument->purchase_aggrement_url) || $meetingDocument->purchase_aggrement_url == null) {
                                    $purchase_aggrement_url++;
                                }
                                if (empty($meetingDocument->strata_title_url) || $meetingDocument->strata_title_url == null) {
                                    $strata_title_url++;
                                }
                                if (empty($meetingDocument->maenance_statement_url) || $meetingDocument->maenance_statement_url == null) {
                                    $maenance_statement_url++;
                                }
                                if (empty($meetingDocument->egrity_pledge_url) || $meetingDocument->egrity_pledge_url == null) {
                                    $egrity_pledge_url++;
                                }
                                if (empty($meetingDocument->report_audited_financial_url) || $meetingDocument->report_audited_financial_url == null) {
                                    $report_audited_financial_url++;
                                }
                                if (empty($meetingDocument->house_rules_url) || $meetingDocument->house_rules_url == null) {
                                    $house_rules_url++;
                                }
                                if (empty($meetingDocument->audit_start_date) || $meetingDocument->audit_start_date <= 0) {
                                    $audit_start_date++;
                                }
                                if (empty($meetingDocument->audit_end_date) || $meetingDocument->audit_end_date <= 0) {
                                    $audit_end_date++;
                                }

                                $total_agm++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total AGM') => $total_agm,
                    trans('app.forms.agm_date') => ($total_agm - $agm_date),
                    trans('app.forms.annual_general_meeting') => ($total_agm - $agm),
                    trans('app.forms.upload_notice_agm_egm') => ($total_agm - $agm_file_url),
                    trans('app.forms.extra_general_meeting') => ($total_agm - $egm),
                    trans('app.forms.upload_minutes_agm_egm') => ($total_agm - $egm_file_url),
                    trans('app.forms.meeting_minutes') => ($total_agm - $minit_meeting),
                    trans('app.forms.upload_minutes_ajk') => ($total_agm - $minutes_meeting_file_url),
                    trans('app.forms.jmc_spa_copy') => ($total_agm - $jmc_spa),
                    trans('app.forms.pledge_letter_of_egrity') => ($total_agm - $jmc_file_url),
                    trans('app.forms.identity_card_list') => ($total_agm - $identity_card),
                    trans('Identity Card List File') => ($total_agm - $ic_file_url),
                    trans('app.forms.attendance_list') => ($total_agm - $attendance),
                    trans('Attendance List File') => ($total_agm - $attendance_file_url),
                    trans('app.forms.audited_financial_report') => ($total_agm - $financial_report),
                    trans('Audited Financial Report File') => ($total_agm - $audited_financial_file_url),
                    trans('app.forms.financial_audit_report') => ($total_agm - $audit_report),
                    trans('Financial Audit Report File') => ($total_agm - $audit_report_url),
                    trans('app.forms.pledge_letter_of_egrity') => ($total_agm - $letter_egrity_url),
                    trans('app.forms.declaration_letter_of_non_bankruptcy') => ($total_agm - $letter_bankruptcy_url),
                    trans('app.forms.upload_notice_agm_egm') => ($total_agm - $notice_agm_egm_url),
                    trans('app.forms.upload_minutes_agm_egm') => ($total_agm - $minutes_agm_egm_url),
                    trans('app.forms.upload_minutes_ajk') => ($total_agm - $minutes_ajk_url),
                    trans('app.forms.upload_eligible_vote') => ($total_agm - $eligible_vote_url),
                    trans('app.forms.upload_attend_meeting') => ($total_agm - $attend_meeting_url),
                    trans('app.forms.upload_proksi') => ($total_agm - $proksi_url),
                    trans('app.forms.upload_ajk_info') => ($total_agm - $ajk_info_url),
                    trans('app.forms.upload_ic') => ($total_agm - $ic_url),
                    trans('app.forms.upload_purchase_aggrement') => ($total_agm - $purchase_aggrement_url),
                    trans('app.forms.upload_strata_title') => ($total_agm - $strata_title_url),
                    trans('app.forms.upload_maenance_statement') => ($total_agm - $maenance_statement_url),
                    trans('app.forms.upload_egrity_pledge') => ($total_agm - $egrity_pledge_url),
                    trans('app.forms.upload_report_audited_financial') => ($total_agm - $report_audited_financial_url),
                    trans('app.forms.upload_house_rules') => ($total_agm - $house_rules_url),
                    trans('app.forms.financial_audit_start_date') => ($total_agm - $audit_start_date),
                    trans('app.forms.financial_audit_end_date') => ($total_agm - $audit_end_date),
                ];
            }
        }

        return $this->result($result, $filename = 'AGM');
    }

    public function owner($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_owner = 0;
                $name = 0;
                $unit_no = 0;
                $unit_share = 0;
                $ic_company_no = 0;
                $address = 0;
                $phone_no = 0;
                $email = 0;
                $race = 0;
                $nationality = 0;
                $no_petak = 0;
                $no_petak_aksesori = 0;
                $keluasan_lantai_petak = 0;
                $keluasan_lantai_petak_aksesori = 0;
                $jenis_kegunaan = 0;
                $nama2 = 0;
                $ic_no2 = 0;
                $alamat_surat_menyurat = 0;
                $caj_penyelenggaraan = 0;
                $sinking_fund = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->owner) {
                            foreach ($files->owner as $owner) {
                                if (empty($owner->owner_name) || $owner->owner_name == null) {
                                    $name++;
                                }
                                if (empty($owner->unit_no) || $owner->unit_no == null) {
                                    $unit_no++;
                                }
                                if (empty($owner->unit_share) || $owner->unit_share == null) {
                                    $unit_share++;
                                }
                                if (empty($owner->ic_company_no) || $owner->ic_company_no == null) {
                                    $ic_company_no++;
                                }
                                if (empty($owner->address) || $owner->address == null) {
                                    $address++;
                                }
                                if (empty($owner->phone_no) || $owner->phone_no == null) {
                                    $phone_no++;
                                }
                                if (empty($owner->email) || $owner->email == null) {
                                    $email++;
                                }
                                if (empty($owner->race_id) || $owner->race_id == 0) {
                                    $race++;
                                }
                                if (empty($owner->nationality_id) || $owner->nationality_id == 0) {
                                    $nationality++;
                                }
                                if (empty($owner->no_petak) || $owner->no_petak == null) {
                                    $no_petak++;
                                }
                                if (empty($owner->no_petak_aksesori) || $owner->no_petak_aksesori == null) {
                                    $no_petak_aksesori++;
                                }
                                if (empty($owner->keluasan_lantai_petak) || $owner->keluasan_lantai_petak == null) {
                                    $keluasan_lantai_petak++;
                                }
                                if (empty($owner->keluasan_lantai_petak_aksesori) || $owner->keluasan_lantai_petak_aksesori == 0) {
                                    $keluasan_lantai_petak_aksesori++;
                                }
                                if (empty($owner->jenis_kegunaan) || $owner->jenis_kegunaan == null) {
                                    $jenis_kegunaan++;
                                }
                                if (empty($owner->nama2) || $owner->nama2 == 0) {
                                    $nama2++;
                                }
                                if (empty($owner->ic_no2) || $owner->ic_no2 == 0) {
                                    $ic_no2++;
                                }
                                if (empty($owner->alamat_surat_menyurat) || $owner->alamat_surat_menyurat == 0) {
                                    $alamat_surat_menyurat++;
                                }
                                if (empty($owner->caj_penyelenggaraan) || $owner->caj_penyelenggaraan == 0) {
                                    $caj_penyelenggaraan++;
                                }
                                if (empty($owner->sinking_fund) || $owner->sinking_fund == 0) {
                                    $sinking_fund++;
                                }

                                $total_owner++;
                            }
                        }
                    }

                    $total_council = count($council->files);

                    $result[$council->short_name] = [
                        trans('Council') => $council->short_name,
                        trans('Total Files') => $total_council,
                        trans('Total Owner') => $total_owner,
                        trans('app.forms.name') => ($total_owner - $name),
                        trans('app.forms.unit_number') => ($total_owner - $unit_no),
                        trans('app.forms.unit_share') => ($total_owner - $unit_share),
                        trans('app.forms.ic_company_number') => ($total_owner - $ic_company_no),
                        trans('app.forms.address') => ($total_owner - $address),
                        trans('app.forms.phone_number') => ($total_owner - $phone_no),
                        trans('app.forms.email') => ($total_owner - $email),
                        trans('app.forms.race') => ($total_owner - $race),
                        trans('app.forms.nationality') => ($total_owner - $nationality),
                        trans('app.forms.no_petak') => ($total_owner - $no_petak),
                        trans('app.forms.no_petak_aksesori') => ($total_owner - $no_petak_aksesori),
                        trans('app.forms.keluasan_lantai_petak') => ($total_owner - $keluasan_lantai_petak),
                        trans('app.forms.keluasan_lantai_petak_aksesori') => ($total_owner - $keluasan_lantai_petak_aksesori),
                        trans('app.forms.jenis_kegunaan') => ($total_owner - $jenis_kegunaan),
                        trans('app.forms.nama2') => ($total_owner - $nama2),
                        trans('app.forms.ic_no2') => ($total_owner - $ic_no2),
                        trans('app.forms.alamat_surat_menyurat') => ($total_owner - $alamat_surat_menyurat),
                        trans('app.forms.caj_penyelenggaraan') => ($total_owner - $caj_penyelenggaraan),
                        trans('app.forms.sinking_fund') => ($total_owner - $sinking_fund),
                    ];
                }
            }
        }

        return $this->result($result, $filename = 'Owner');
    }

    public function tenant($cob) {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_tenant = 0;
                $name = 0;
                $unit_no = 0;
                $unit_share = 0;
                $ic_company_no = 0;
                $address = 0;
                $phone_no = 0;
                $email = 0;
                $race = 0;
                $nationality = 0;
                $no_petak = 0;
                $no_petak_aksesori = 0;
                $keluasan_lantai_petak = 0;
                $keluasan_lantai_petak_aksesori = 0;
                $jenis_kegunaan = 0;
                $nama2 = 0;
                $ic_no2 = 0;
                $alamat_surat_menyurat = 0;
                $caj_penyelenggaraan = 0;
                $sinking_fund = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->owner) {
                            foreach ($files->tenant as $tenant) {
                                if (empty($tenant->tenant_name) || $tenant->tenant_name == null) {
                                    $name++;
                                }
                                if (empty($tenant->unit_no) || $tenant->unit_no == null) {
                                    $unit_no++;
                                }
                                if (empty($tenant->unit_share) || $tenant->unit_share == null) {
                                    $unit_share++;
                                }
                                if (empty($tenant->ic_company_no) || $tenant->ic_company_no == null) {
                                    $ic_company_no++;
                                }
                                if (empty($tenant->address) || $tenant->address == null) {
                                    $address++;
                                }
                                if (empty($tenant->phone_no) || $tenant->phone_no == null) {
                                    $phone_no++;
                                }
                                if (empty($tenant->email) || $tenant->email == null) {
                                    $email++;
                                }
                                if (empty($tenant->race_id) || $tenant->race_id == 0) {
                                    $race++;
                                }
                                if (empty($tenant->nationality_id) || $tenant->nationality_id == 0) {
                                    $nationality++;
                                }
                                if (empty($tenant->no_petak) || $tenant->no_petak == null) {
                                    $no_petak++;
                                }
                                if (empty($tenant->no_petak_aksesori) || $tenant->no_petak_aksesori == null) {
                                    $no_petak_aksesori++;
                                }
                                if (empty($tenant->keluasan_lantai_petak) || $tenant->keluasan_lantai_petak == null) {
                                    $keluasan_lantai_petak++;
                                }
                                if (empty($tenant->keluasan_lantai_petak_aksesori) || $tenant->keluasan_lantai_petak_aksesori == 0) {
                                    $keluasan_lantai_petak_aksesori++;
                                }
                                if (empty($tenant->jenis_kegunaan) || $tenant->jenis_kegunaan == null) {
                                    $jenis_kegunaan++;
                                }
                                if (empty($tenant->nama2) || $tenant->nama2 == 0) {
                                    $nama2++;
                                }
                                if (empty($tenant->ic_no2) || $tenant->ic_no2 == 0) {
                                    $ic_no2++;
                                }
                                if (empty($tenant->alamat_surat_menyurat) || $tenant->alamat_surat_menyurat == 0) {
                                    $alamat_surat_menyurat++;
                                }
                                if (empty($tenant->caj_penyelenggaraan) || $tenant->caj_penyelenggaraan == 0) {
                                    $caj_penyelenggaraan++;
                                }
                                if (empty($tenant->sinking_fund) || $tenant->sinking_fund == 0) {
                                    $sinking_fund++;
                                }

                                $total_tenant++;
                            }
                        }
                    }

                    $total_council = count($council->files);

                    $result[$council->short_name] = [
                        trans('Council') => $council->short_name,
                        trans('Total Files') => $total_council,
                        trans('Total Tenant') => $total_tenant,
                        trans('app.forms.name') => ($total_tenant - $name),
                        trans('app.forms.unit_number') => ($total_tenant - $unit_no),
                        trans('app.forms.unit_share') => ($total_tenant - $unit_share),
                        trans('app.forms.ic_company_number') => ($total_tenant - $ic_company_no),
                        trans('app.forms.address') => ($total_tenant - $address),
                        trans('app.forms.phone_number') => ($total_tenant - $phone_no),
                        trans('app.forms.email') => ($total_tenant - $email),
                        trans('app.forms.race') => ($total_tenant - $race),
                        trans('app.forms.nationality') => ($total_tenant - $nationality),
                        trans('app.forms.no_petak') => ($total_tenant - $no_petak),
                        trans('app.forms.no_petak_aksesori') => ($total_tenant - $no_petak_aksesori),
                        trans('app.forms.keluasan_lantai_petak') => ($total_tenant - $keluasan_lantai_petak),
                        trans('app.forms.keluasan_lantai_petak_aksesori') => ($total_tenant - $keluasan_lantai_petak_aksesori),
                        trans('app.forms.jenis_kegunaan') => ($total_tenant - $jenis_kegunaan),
                        trans('app.forms.nama2') => ($total_tenant - $nama2),
                        trans('app.forms.ic_no2') => ($total_tenant - $ic_no2),
                        trans('app.forms.alamat_surat_menyurat') => ($total_tenant - $alamat_surat_menyurat),
                        trans('app.forms.caj_penyelenggaraan') => ($total_tenant - $caj_penyelenggaraan),
                        trans('app.forms.sinking_fund') => ($total_tenant - $sinking_fund),
                    ];
                }
            }
        }

        return $this->result($result, $filename = 'Tenant');
    }

}