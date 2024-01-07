<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;

class LPHSController extends BaseController
{

    function randomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function getYear()
    {
        $min_year = Files::where('year', '>', 0)->min('year');
        $max_year = date('Y');

        $year = [];
        for ($max_year; $max_year >= $min_year; $max_year--) {
            $year[] = (int) $max_year;
        }

        return $year;
    }

    public function council($cob)
    {
        if (!empty($cob) && $cob != 'all') {
            $councils = Company::where('short_name', strtoupper($cob))->where('is_main', 0)->where('is_deleted', 0)->orderBy('short_name')->get();
        } else {
            $councils = Company::where('is_main', 0)->where('is_deleted', 0)->orderBy('short_name')->get();
        }

        return $councils;
    }

    public function result($result, $filename, $output = 'excel')
    {
        if ($output == 'excel') {
            Excel::create($filename . '_' . date('YmdHis'), function ($excel) use ($filename, $result) {
                $excel->sheet($filename, function ($sheet) use ($result) {
                    $sheet->fromArray($result);
                });
            })->export('xlsx');
        }

        return '<pre>' . json_encode($result, JSON_PRETTY_PRINT) . '</pre>';
    }

    public function removeJMB($cob)
    {
        $council = Company::where('short_name', $cob)->where('is_main', 0)->where('is_deleted', 0)->firstOrFail();

        User::where('company_id', $council->id)->where('remarks', 'Created by System')->delete();

        return 'Success delete';
    }

    public function createJMB($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    $file_lists = Files::doesntHave('jmb')->where('company_id', $council->id)->where('is_deleted', 0)->orderBy('id')->take(500)->get();

                    if ($file_lists) {
                        foreach ($file_lists as $files) {
                            if (!$files->jmb) {

                                $raw_file_no = preg_replace('/[^\p{L}\p{N}\s]/u', '', $files->file_no);
                                $generated_file_no = str_replace(' ', '', $raw_file_no);

                                $council_name = $council->short_name;
                                $file_no = $files->file_no;
                                $file_name = $files->strata->name;
                                $username = strtolower($generated_file_no);
                                $password = $this->randomString();
                                $full_name = strtoupper($generated_file_no);
                                $email = '';
                                $phone_no = '';
                                $role = Role::where('name', Role::JMB)->pluck('id');
                                $start_date = Carbon::now()->format('Y-m-d');
                                $end_date = Carbon::now()->addMonth(2)->format('Y-m-d');
                                $file_id = $files->id;
                                $company_id = $council->id;
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
        }

        return $this->result($result, $filename = 'JMB_' . $council->short_name);
    }

    public function createJMBBak($cob)
    {
        $council = Company::where('short_name', $cob)->where('is_main', 0)->where('is_deleted', 0)->firstOrFail();
        $filename = 'JMB_' . $council->short_name;

        $orders = Files::doesntHave('jmb')->where('company_id', $council->id)->where('is_deleted', 0)->orderBy('id');

        Excel::create($filename, function ($excel) use ($orders, $council) {
            $excel->sheet($council->short_name, function ($sheet) use ($orders, $council) {

                $sheet->appendRow(array(
                    trans('app.menus.reporting.council'),
                    trans('app.forms.file_no'),
                    trans('app.forms.file_name'),
                    trans('app.forms.username'),
                    trans('app.forms.password'),
                    trans('app.forms.date_start'),
                    trans('app.forms.date_end')
                ));

                $orders->take(5)->chunk(500, function ($rows) use ($sheet, $council) {
                    foreach ($rows as $files) {
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
                            $user->remarks = $remarks;
                            $user->is_active = $is_active;
                            $user->is_deleted = $is_deleted;
                            $user->status = $status;
                            $user->approved_by = $approved_by;
                            $user->approved_at = $approved_at;
                            $success = $user->save();

                            if ($success) {
                                $sheet->appendRow(array(
                                    $council_name,
                                    $file_no,
                                    $file_name,
                                    $username,
                                    $password,
                                    $start_date,
                                    $end_date
                                ));
                            }
                        }
                    }
                });
            });
        })->download('xlsx');
    }

    public function finance($cob = null, $year = null)
    {
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

    public function developer($cob = null)
    {
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

    public function strata($cob = null)
    {
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

    public function management($cob)
    {
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

    public function jmb($cob)
    {
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

    public function mc($cob)
    {
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

    public function agent($cob)
    {
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

    public function others($cob)
    {
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

    public function agm($cob)
    {
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

    public function owner($cob)
    {
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

    public function tenant($cob)
    {
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

    public function updateJMBExpiration($cob = null, $date = null)
    {
        $councils = $this->council($cob);
        if ($councils) {
            foreach ($councils as $council) {
                $jmb_role = Role::where('name', Role::JMB)->pluck('id');
                $users = User::where('role', $jmb_role)
                    ->where('company_id', $council->id)
                    // ->where('remarks', 'Created by System')
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();

                foreach ($users as $user) {
                    $user->end_date = (!empty($date) ? $date : date('Y') . '-12-31');
                    $user->save();
                }
            }
        }

        return 'update done';
    }

    public function updateRatingSummary()
    {
        if (Auth::check() && Auth::user()->getAdmin()) {
            $items = Scoring::where('is_deleted', 0)->get();

            foreach ($items as $item) {
                $item_date = date('Y-m-d', strtotime($item->updated_at));
                if ($item_date < date('Y-m-d') && date('Y-m-d') == '2021-09-22') {
                    $item->score1 = ($item->score1 > 0) ? (($item->score1 == 5) ? $item->score1 : $item->score1 + 1) : 1;
                    $item->score2 = ($item->score2 > 0) ? (($item->score2 == 5) ? $item->score2 : $item->score2 + 1) : 1;
                    $item->score3 = ($item->score3 > 0) ? (($item->score3 == 5) ? $item->score3 : $item->score3 + 1) : 1;
                    $item->score4 = ($item->score4 > 0) ? (($item->score4 == 5) ? $item->score4 : $item->score4 + 1) : 1;
                    $item->score5 = ($item->score5 > 0) ? (($item->score5 == 5) ? $item->score5 : $item->score5 + 1) : 1;
                    $item->score6 = ($item->score6 > 0) ? (($item->score6 == 5) ? $item->score6 : $item->score6 + 1) : 1;
                    $item->score7 = ($item->score7 > 0) ? (($item->score7 == 5) ? $item->score7 : $item->score7 + 1) : 1;
                    $item->score8 = ($item->score8 > 0) ? (($item->score8 == 5) ? $item->score8 : $item->score8 + 1) : 1;
                    $item->score9 = ($item->score9 > 0) ? (($item->score9 == 5) ? $item->score9 : $item->score9 + 1) : 1;
                    $item->score10 = ($item->score10 > 0) ? (($item->score10 == 5) ? $item->score10 : $item->score10 + 1) : 1;
                    $item->score11 = ($item->score11 > 0) ? (($item->score11 == 5) ? $item->score11 : $item->score11 + 1) : 1;
                    $item->score12 = ($item->score12 > 0) ? (($item->score12 == 5) ? $item->score12 : $item->score12 + 1) : 1;
                    $item->score13 = ($item->score13 > 0) ? (($item->score13 == 5) ? $item->score13 : $item->score13 + 1) : 1;
                    $item->score14 = ($item->score14 > 0) ? (($item->score14 == 5) ? $item->score14 : $item->score14 + 1) : 1;
                    $item->score15 = ($item->score15 > 0) ? (($item->score15 == 5) ? $item->score15 : $item->score15 + 1) : 1;
                    $item->score16 = ($item->score16 > 0) ? (($item->score16 == 5) ? $item->score16 : $item->score16 + 1) : 1;
                    $item->score17 = ($item->score17 > 0) ? (($item->score17 == 5) ? $item->score17 : $item->score17 + 1) : 1;
                    $item->score18 = ($item->score18 > 0) ? (($item->score18 == 5) ? $item->score18 : $item->score18 + 1) : 1;
                    $item->score19 = ($item->score19 > 0) ? (($item->score19 == 5) ? $item->score19 : $item->score19 + 1) : 1;
                    $item->score20 = ($item->score20 > 0) ? (($item->score20 == 5) ? $item->score20 : $item->score20 + 1) : 1;
                    $item->score21 = ($item->score21 > 0) ? (($item->score21 == 5) ? $item->score21 : $item->score21 + 1) : 1;
                    $scorings_A = ((($item->score1 + $item->score2 + $item->score3 + $item->score4 + $item->score5) / 25) * 25);
                    $scorings_B = ((($item->score6 + $item->score7 + $item->score8 + $item->score9 + $item->score10) / 25) * 25);
                    $scorings_C = ((($item->score11 + $item->score12 + $item->score13 + $item->score14) / 20) * 20);
                    $scorings_D = ((($item->score15 + $item->score16 + $item->score17 + $item->score18) / 20) * 20);
                    $scorings_E = ((($item->score19 + $item->score20 + $item->score21) / 15) * 10);
                    $item->total_score = $scorings_A + $scorings_B + $scorings_C + $scorings_D + $scorings_E;
                    $item->save();
                }
            }

            return [
                'success' => true,
                'message' => 'done'
            ];
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

    public function odesiLife($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $files) {
                        if ($files->strata) {
                            $total_unit = 0;
                            if ($files->strata->residential) {
                                if ($files->strata->residential->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->residential->unit_no;
                                }
                            }
                            if ($files->strata->commercial) {
                                if ($files->strata->commercial->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->commercial->unit_no;
                                }
                            }

                            $developer_name = '';
                            $developer_address1 = '';
                            $developer_address2 = '';
                            $developer_address3 = '';
                            $developer_address4 = '';
                            $developer_postcode = '';
                            $developer_city = '';
                            $developer_state = '';
                            $developer_phone_no = '';
                            $developer_email = '';
                            if ($files->managementDeveloper) {
                                if ($files->managementDeveloper->name) {
                                    $developer_name = $files->managementDeveloper->name;
                                }
                                if ($files->managementDeveloper->address_1) {
                                    $developer_address1 = $files->managementDeveloper->address_1;
                                }
                                if ($files->managementDeveloper->address_2) {
                                    $developer_address2 = $files->managementDeveloper->address_2;
                                }
                                if ($files->managementDeveloper->address_3) {
                                    $developer_address3 = $files->managementDeveloper->address_3;
                                }
                                if ($files->managementDeveloper->address_4) {
                                    $developer_address4 = $files->managementDeveloper->address_4;
                                }
                                if ($files->managementDeveloper->poscode) {
                                    $developer_postcode = $files->managementDeveloper->poscode;
                                }
                                if ($files->managementDeveloper->city) {
                                    $developer_city = $files->managementDeveloper->cities->description;
                                }
                                if ($files->managementDeveloper->state) {
                                    $developer_state = $files->managementDeveloper->states->name;
                                }
                                if ($files->managementDeveloper->phone_no) {
                                    $developer_phone_no = $files->managementDeveloper->phone_no;
                                }
                                if ($files->managementDeveloper->email) {
                                    $developer_email = $files->managementDeveloper->email;
                                }
                            }

                            $jmb_name = '';
                            $jmb_address1 = '';
                            $jmb_address2 = '';
                            $jmb_address3 = '';
                            $jmb_address4 = '';
                            $jmb_postcode = '';
                            $jmb_city = '';
                            $jmb_state = '';
                            $jmb_phone_no = '';
                            $jmb_email = '';
                            if ($files->managementJMB) {
                                if ($files->managementJMB->name) {
                                    $jmb_name = $files->managementJMB->name;
                                }
                                if ($files->managementJMB->address1) {
                                    $jmb_address1 = $files->managementJMB->address1;
                                }
                                if ($files->managementJMB->address2) {
                                    $jmb_address2 = $files->managementJMB->address2;
                                }
                                if ($files->managementJMB->address3) {
                                    $jmb_address3 = $files->managementJMB->address3;
                                }
                                if ($files->managementJMB->address4) {
                                    $jmb_address4 = $files->managementJMB->address4;
                                }
                                if ($files->managementJMB->poscode) {
                                    $jmb_postcode = $files->managementJMB->poscode;
                                }
                                if ($files->managementJMB->city) {
                                    $jmb_city = $files->managementJMB->cities->description;
                                }
                                if ($files->managementJMB->state) {
                                    $jmb_state = $files->managementJMB->states->name;
                                }
                                if ($files->managementJMB->phone_no) {
                                    $jmb_phone_no = $files->managementJMB->phone_no;
                                }
                                if ($files->managementJMB->email) {
                                    $jmb_email = $files->managementJMB->email;
                                }
                            }

                            $mc_name = '';
                            $mc_address1 = '';
                            $mc_address2 = '';
                            $mc_address3 = '';
                            $mc_address4 = '';
                            $mc_postcode = '';
                            $mc_city = '';
                            $mc_state = '';
                            $mc_phone_no = '';
                            $mc_email = '';
                            if ($files->managementMC) {
                                if ($files->managementMC->name) {
                                    $mc_name = $files->managementMC->name;
                                }
                                if ($files->managementMC->address1) {
                                    $mc_address1 = $files->managementMC->address1;
                                }
                                if ($files->managementMC->address2) {
                                    $mc_address2 = $files->managementMC->address2;
                                }
                                if ($files->managementMC->address3) {
                                    $mc_address3 = $files->managementMC->address3;
                                }
                                if ($files->managementMC->address4) {
                                    $mc_address4 = $files->managementMC->address4;
                                }
                                if ($files->managementMC->poscode) {
                                    $mc_postcode = $files->managementMC->poscode;
                                }
                                if ($files->managementMC->city) {
                                    $mc_city = $files->managementMC->cities->description;
                                }
                                if ($files->managementMC->state) {
                                    $mc_state = $files->managementMC->states->name;
                                }
                                if ($files->managementMC->phone_no) {
                                    $mc_phone_no = $files->managementMC->phone_no;
                                }
                                if ($files->managementMC->email) {
                                    $mc_email = $files->managementMC->email;
                                }
                            }

                            $agent_name = '';
                            $agent_address1 = '';
                            $agent_address2 = '';
                            $agent_address3 = '';
                            $agent_address4 = '';
                            $agent_postcode = '';
                            $agent_city = '';
                            $agent_state = '';
                            $agent_phone_no = '';
                            $agent_email = '';
                            if ($files->managementAgent) {
                                if ($files->managementAgent->name) {
                                    $agent_name = $files->managementAgent->name;
                                }
                                if ($files->managementAgent->address1) {
                                    $agent_address1 = $files->managementAgent->address1;
                                }
                                if ($files->managementAgent->address2) {
                                    $agent_address2 = $files->managementAgent->address2;
                                }
                                if ($files->managementAgent->address3) {
                                    $agent_address3 = $files->managementAgent->address3;
                                }
                                if ($files->managementAgent->address4) {
                                    $agent_address4 = $files->managementAgent->address4;
                                }
                                if ($files->managementAgent->poscode) {
                                    $agent_postcode = $files->managementAgent->poscode;
                                }
                                if ($files->managementAgent->city) {
                                    $agent_city = $files->managementAgent->cities->description;
                                }
                                if ($files->managementAgent->state) {
                                    $agent_state = $files->managementAgent->states->name;
                                }
                                if ($files->managementAgent->phone_no) {
                                    $agent_phone_no = $files->managementAgent->phone_no;
                                }
                                if ($files->managementAgent->email) {
                                    $agent_email = $files->managementAgent->email;
                                }
                            }

                            $others_name = '';
                            $others_address1 = '';
                            $others_address2 = '';
                            $others_address3 = '';
                            $others_address4 = '';
                            $others_postcode = '';
                            $others_city = '';
                            $others_state = '';
                            $others_phone_no = '';
                            $others_email = '';
                            if ($files->managementOthers) {
                                if ($files->managementOthers->name) {
                                    $others_name = $files->managementOthers->name;
                                }
                                if ($files->managementOthers->address1) {
                                    $others_address1 = $files->managementOthers->address1;
                                }
                                if ($files->managementOthers->address2) {
                                    $others_address2 = $files->managementOthers->address2;
                                }
                                if ($files->managementOthers->address3) {
                                    $others_address3 = $files->managementOthers->address3;
                                }
                                if ($files->managementOthers->address4) {
                                    $others_address4 = $files->managementOthers->address4;
                                }
                                if ($files->managementOthers->poscode) {
                                    $others_postcode = $files->managementOthers->poscode;
                                }
                                if ($files->managementOthers->city) {
                                    $others_city = $files->managementOthers->cities->description;
                                }
                                if ($files->managementOthers->state) {
                                    $others_state = $files->managementOthers->states->name;
                                }
                                if ($files->managementOthers->phone_no) {
                                    $others_phone_no = $files->managementOthers->phone_no;
                                }
                                if ($files->managementOthers->email) {
                                    $others_email = $files->managementOthers->email;
                                }
                            }

                            $pic_name = '';
                            $pic_phone_no = '';
                            $pic_email = '';
                            if ($files->personInCharge) {
                                foreach ($files->personInCharge as $pic) {
                                    if ($pic->user->full_name) {
                                        $pic_name = $pic->user->full_name;
                                    }
                                    if ($pic->user->phone_no) {
                                        $pic_phone_no = $pic->user->phone_no;
                                    }
                                    if ($pic->user->email) {
                                        $pic_email = $pic->user->email;
                                    }
                                }
                            }

                            $result[] = [
                                trans('Council') => $council->name . ' (' . $council->short_name . ')',
                                trans('File No') => $files->file_no,
                                trans('Building Name') => $files->strata->name,
                                trans('Address 1') => $files->strata->address1,
                                trans('Address 2') => $files->strata->address2,
                                trans('Address 3') => $files->strata->address3,
                                trans('Address 4') => $files->strata->address4,
                                trans('Postcode') => $files->strata->poscode,
                                trans('City') => ($files->strata->city ? $files->strata->cities->description : ''),
                                trans('State') => ($files->strata->state ? $files->strata->states->name : ''),
                                trans('Land Title') => ($files->strata->landTitle ? $files->strata->landTitle->description : ''),
                                trans('Category') => ($files->strata->categories ? $files->strata->categories->description : ''),
                                trans('No. of Block') => $files->strata->block_no,
                                trans('Total Floor') => $files->strata->total_floor,
                                trans('Total Unit') => $total_unit,

                                trans('PIC Name') => $pic_name,
                                trans('PIC Phone No') => $pic_phone_no,
                                trans('PIC E-mail') => $pic_email,

                                trans('Developer Name') => $developer_name,
                                trans('Developer Address 1') => $developer_address1,
                                trans('Developer Address 2') => $developer_address2,
                                trans('Developer Address 3') => $developer_address3,
                                trans('Developer Address 4') => $developer_address4,
                                trans('Developer Postcode') => $developer_postcode,
                                trans('Developer City') => $developer_city,
                                trans('Developer State') => $developer_state,
                                trans('Developer Phone No') => $developer_phone_no,
                                trans('Developer E-mail') => $developer_email,

                                trans('JMB Name') => $jmb_name,
                                trans('JMB Address 1') => $jmb_address1,
                                trans('JMB Address 2') => $jmb_address2,
                                trans('JMB Address 3') => $jmb_address3,
                                trans('JMB Address 4') => $jmb_address4,
                                trans('JMB Postcode') => $jmb_postcode,
                                trans('JMB City') => $jmb_city,
                                trans('JMB State') => $jmb_state,
                                trans('JMB Phone No') => $jmb_phone_no,
                                trans('JMB E-mail') => $jmb_email,

                                trans('MC Name') => $mc_name,
                                trans('MC Address 1') => $mc_address1,
                                trans('MC Address 2') => $mc_address2,
                                trans('MC Address 3') => $mc_address3,
                                trans('MC Address 4') => $mc_address4,
                                trans('MC Postcode') => $mc_postcode,
                                trans('MC City') => $mc_city,
                                trans('MC State') => $mc_state,
                                trans('MC Phone No') => $mc_phone_no,
                                trans('MC E-mail') => $mc_email,

                                trans('Agent Name') => $agent_name,
                                trans('Agent Address 1') => $agent_address1,
                                trans('Agent Address 2') => $agent_address2,
                                trans('Agent Address 3') => $agent_address3,
                                trans('Agent Address 4') => $agent_address4,
                                trans('Agent Postcode') => $agent_postcode,
                                trans('Agent City') => $agent_city,
                                trans('Agent State') => $agent_state,
                                trans('Agent Phone No') => $agent_phone_no,
                                trans('Agent E-mail') => $agent_email,

                                trans('Others Name') => $others_name,
                                trans('Others Address 1') => $others_address1,
                                trans('Others Address 2') => $others_address2,
                                trans('Others Address 3') => $others_address3,
                                trans('Others Address 4') => $others_address4,
                                trans('Others Postcode') => $others_postcode,
                                trans('Others City') => $others_city,
                                trans('Others State') => $others_state,
                                trans('Others Phone No') => $others_phone_no,
                                trans('Others E-mail') => $others_email,
                            ];
                        }
                    }
                }
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = strtoupper($cob));
    }

    public function JMBMCSignIn($cob = null)
    {
        $result = [];

        $council = Company::where('short_name', $cob)->first();
        if ($council) {
            $users = User::where('company_id', $council->id)
                ->where('file_id', '!=', '')
                ->orderBy('file_id')
                ->get();

            if ($users) {
                foreach ($users as $user) {
                    if ($user->hasSignedIn) {
                        $file = Files::find($user->file_id);
                        if ($file) {
                            $auditTrails = AuditTrail::where('audit_by', $user->id)
                                ->where('remarks', 'like', '%' . $file->file_no . '%')
                                ->get();

                            $result[] = [
                                trans('Username') => $user->username,
                                trans('Name') => $user->full_name,
                                trans('E-mail') => $user->email,
                                trans('Phone') => $user->phone_no,
                                trans('Role') => ($user->isJMB() ? 'JMB' : 'MC'),
                                trans('Start Date') => $user->start_date,
                                trans('End Date') => $user->end_date,
                                trans('Remarks') => $user->remarks,
                                trans('Login At') => $user->hasSignedIn->created_at->format('Y-m-d H:i:s'),
                                trans('File No.') => $file->file_no,
                                trans('Strata') => ($file->strata ? $file->strata->name : ''),
                                trans('Self Update') => ($auditTrails->count() > 0 ? 'Yes' : 'No')
                            ];
                        }
                    }
                }
            }
        }

        return $this->result($result, strtoupper($cob), 'excel');
    }

    public function updateByUser($username = null)
    {
        $result = [];

        $user = User::where('username', $username)->first();
        if ($user) {
            $auditTrails = AuditTrail::where('audit_by', $user->id)->get();

            $result[] = [
                trans('Username') => $user->username,
                trans('Name') => $user->full_name,
                trans('E-mail') => $user->email,
                trans('Phone') => $user->phone_no,
                trans('Role') => ($user->isJMB() ? 'JMB' : 'MC'),
                trans('Start Date') => $user->start_date,
                trans('End Date') => $user->end_date,
                trans('Remarks') => $user->remarks,
                trans('Audit Trail') => $auditTrails->toArray()
            ];
        }

        return $this->result($result, strtoupper($username), '');
    }

    public function neverHasAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->where(function ($query) {
                $query->whereDoesntHave('meetingDocument');
                $query->orWhereHas('meetingDocument', function ($query2) {
                    $query2->where('meeting_document.agm_date', '0000-00-00');
                    $query2->where('meeting_document.is_deleted', 0);
                });
            })
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('files.file_no');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Never Has AGM - ' . strtoupper($cob));
    }

    public function due12MonthsAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->leftjoin('meeting_document', 'files.id', '=', 'meeting_document.file_id')
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-12 Months')))
            ->where('meeting_document.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('meeting_document.agm_date', 'desc');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name', 'meeting_document.agm_date as agm_date')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Last AGM Date') => $item->agm_date,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Due 12 months AGM - ' . strtoupper($cob));
    }

    public function due15MonthsAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->leftjoin('meeting_document', 'files.id', '=', 'meeting_document.file_id')
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-15 Months')))
            ->where('meeting_document.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('meeting_document.agm_date', 'desc');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name', 'meeting_document.agm_date as agm_date')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Last AGM Date') => $item->agm_date,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Due 15 months AGM - ' . strtoupper($cob));
    }

    public function insurance($cob = null)
    {
        $result = [];

        $query = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
            ->leftjoin('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->where('insurance.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('files.file_no');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'insurance_provider.name as provider', 'insurance.*')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Insurance Provider') => $item->provider,
                    trans('Public Liability Coverage (PLC)') => $item->public_liability_coverage,
                    trans('PLC Premium Per Year') => $item->plc_premium_per_year,
                    trans('PLC Validity From') => $item->plc_validity_from,
                    trans('PLC Validity To') => $item->plc_validity_to,
                    trans('Fire Insurance Coverage (FIC)') => $item->fire_insurance_coverage,
                    trans('FIC Premium Per Year') => $item->fic_premium_per_year,
                    trans('FIC Validity From') => $item->fic_validity_from,
                    trans('FIC Validity To') => $item->fic_validity_to,
                    trans('Remarks') => $item->remarks,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Insurance - ' . strtoupper($cob));
    }

    public function financeOutstanding($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        if ($file->financeLatest) {
                            $finance = $file->financeLatest;

                            $mf_sepatut_dikutip = $finance->financeReport()->where('type', 'MF')->sum('fee_semasa');
                            $mf_extra_sepatut_dikutip = $finance->financeReportExtra()->where('type', 'MF')->sum('fee_semasa');

                            $sf_sepatut_dikutip = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                            $sf_extra_sepatut_dikutip = $finance->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                            $mf_sf_sepatut_dikutip = $finance->financeReport()->sum('fee_semasa');
                            $mf_sf_extra_sepatut_dikutip = $finance->financeReportExtra()->sum('fee_semasa');

                            $total_mf_sepatut_dikutip = $mf_sepatut_dikutip + $mf_extra_sepatut_dikutip;
                            $total_sf_sepatut_dikutip = $sf_sepatut_dikutip + $sf_extra_sepatut_dikutip;
                            $total_mf_sf_sepatut_dikutip = $mf_sf_sepatut_dikutip + $mf_sf_extra_sepatut_dikutip;

                            $total_mf_berjaya_dikutip = $finance->financeIncome()->where('name', 'MAINTENANCE FEE')->sum('semasa');
                            $total_sf_berjaya_dikutip = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                            $total_mf_sf_berjaya_dikutip = $total_mf_berjaya_dikutip + $total_sf_berjaya_dikutip;

                            $total_mf_outstanding = $total_mf_sepatut_dikutip - $total_mf_berjaya_dikutip;
                            $total_sf_outstanding = $total_sf_sepatut_dikutip - $total_sf_berjaya_dikutip;
                            $total_mf_sf_outstanding = $total_mf_sf_sepatut_dikutip - $total_mf_sf_berjaya_dikutip;

                            $developer_name = '';
                            $developer_phone = '';
                            if ($file->managementDeveloper) {
                                $developer_name = $file->managementDeveloper->name;
                                $developer_phone = $file->managementDeveloper->phone_no;
                            }

                            $jmb_name = '';
                            $jmb_phone = '';
                            if ($file->managementJMB) {
                                $jmb_name = $file->managementJMB->name;
                                $jmb_phone = $file->managementJMB->phone_no;
                            }

                            $mc_name = '';
                            $mc_phone = '';
                            if ($file->managementMC) {
                                $mc_name = $file->managementMC->name;
                                $mc_phone = $file->managementMC->phone_no;
                            }

                            $agent_name = '';
                            $agent_phone = '';
                            if ($file->managementAgent) {
                                $agent_name = $file->managementAgent->name;
                                $agent_phone = $file->managementAgent->phone_no;
                            }

                            $other_name = '';
                            $other_phone = '';
                            if ($file->managementOthers) {
                                $other_name = $file->managementOthers->name;
                                $other_phone = $file->managementOthers->phone_no;
                            }

                            $result[$file->id] = [
                                'Council' => $council->short_name,
                                'File No' => $file->file_no,
                                'Strata Name' => $file->strata->name,
                                'Developer Name' => $developer_name,
                                'Developer Phone No.' => $developer_phone,
                                'JMB Name' => $jmb_name,
                                'JMB Phone No.' => $jmb_phone,
                                'MC Name' => $mc_name,
                                'MC Phone No.' => $mc_phone,
                                'Agent Name' => $agent_name,
                                'Agent Phone No.' => $agent_phone,
                                'Other Name' => $other_name,
                                'Other Phone No.' => $other_phone,
                                'Finance Last Updated' => strtoupper($finance->monthName()) . ' - ' . $finance->year,
                                'MF Amount (RM)' => number_format($total_mf_sepatut_dikutip, 2),
                                'SF Amount (RM)' => number_format($total_sf_sepatut_dikutip, 2),
                                'MF & SF Amount (RM)' => number_format($total_mf_sf_sepatut_dikutip, 2),
                                'Total MF Collected (RM)' => number_format($total_mf_berjaya_dikutip, 2),
                                'Total SF Collected (RM)' => number_format($total_sf_berjaya_dikutip, 2),
                                'Total MF & SF Collected (RM)' => number_format($total_mf_sf_berjaya_dikutip, 2),
                                'Total MF Outstanding (RM)' => number_format($total_mf_outstanding, 2),
                                'Total SF Outstanding (RM)' => number_format($total_sf_outstanding, 2),
                                'Total MF & SF Outstanding (RM)' => number_format($total_mf_sf_outstanding, 2),
                            ];
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Finance_Outstanding_' . strtoupper($cob));
    }

    public function strataByCategory($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $strata = ($file->strata ? $file->strata->name : '');
                        $category = ($file->strata->categories ? $file->strata->categories->description : '');
                        $resident = ($file->resident ? $file->resident->unit_no : 0);
                        $commercial = ($file->commercial ? $file->commercial->unit_no : 0);

                        $developer_name = '';
                        $developer_phone = '';
                        if ($file->managementDeveloper) {
                            $developer_name = $file->managementDeveloper->name;
                            $developer_phone = $file->managementDeveloper->phone_no;
                        }

                        $jmb_name = '';
                        $jmb_phone = '';
                        if ($file->managementJMB) {
                            $jmb_name = $file->managementJMB->name;
                            $jmb_phone = $file->managementJMB->phone_no;
                        }

                        $mc_name = '';
                        $mc_phone = '';
                        if ($file->managementMC) {
                            $mc_name = $file->managementMC->name;
                            $mc_phone = $file->managementMC->phone_no;
                        }

                        $agent_name = '';
                        $agent_phone = '';
                        if ($file->managementAgent) {
                            $agent_name = $file->managementAgent->name;
                            $agent_phone = $file->managementAgent->phone_no;
                        }

                        $other_name = '';
                        $other_phone = '';
                        if ($file->managementOthers) {
                            $other_name = $file->managementOthers->name;
                            $other_phone = $file->managementOthers->phone_no;
                        }

                        $result[$file->id] = [
                            'Council' => $file->company->short_name,
                            'File No' => $file->file_no,
                            'Strata Name' => $strata,
                            'Category' => $category,
                            'Resident' => $resident,
                            'Commercial' => $commercial,
                            'Total Unit' => $resident + $commercial,
                            'Developer Name' => $developer_name,
                            'Developer Phone No.' => $developer_phone,
                            'JMB Name' => $jmb_name,
                            'JMB Phone No.' => $jmb_phone,
                            'MC Name' => $mc_name,
                            'MC Phone No.' => $mc_phone,
                            'Agent Name' => $agent_name,
                            'Agent Phone No.' => $agent_phone,
                            'Other Name' => $other_name,
                            'Other Phone No.' => $other_phone,
                        ];
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Strata_By_Category_' . strtoupper($cob));
    }

    public function electricity($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $result[$file->id] = [];

                        Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                        Arr::set($result[$file->id], 'File No', $file->file_no);
                        Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                        $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                        if ($designations) {
                            foreach ($designations as $designation) {
                                $ajk_detail = AJKDetails::where('file_id', $file->id)
                                    ->where('designation', $designation->id)
                                    ->where('is_deleted', 0)
                                    ->orderBy('start_year', 'desc')
                                    ->orderBy('month', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                            }
                        }

                        $tnb_bill = 0;
                        if ($finance = $file->financeLatest) {
                            $summary = FinanceSummary::where('finance_file_id', $finance->id)
                                ->where('summary_key', 'bill_elektrik')
                                ->first();

                            if ($summary) {
                                $tnb_bill = $summary->amount;
                            }
                        }

                        Arr::set($result[$file->id], 'TNB Bill (RM)', number_format($tnb_bill, 2));

                        // total residential unit
                        $total_residential_unit = 0;
                        if ($resident = $file->resident) {
                            $total_residential_unit = (!empty($resident->unit_no) ? $resident->unit_no : 0);
                        }

                        $total_residential_unit_extra = 0;
                        if ($residentExtra = $file->residentExtra) {
                            $total_residential_unit_extra = (!empty($residentExtra->unit_no) ? $residentExtra->unit_no : 0);
                        }

                        Arr::set($result[$file->id], 'Total Residential Unit', $total_residential_unit + $total_residential_unit_extra);

                        // total commercial unit
                        $total_commercial_unit = 0;
                        if ($commercial = $file->commercial) {
                            $total_commercial_unit = (!empty($commercial->unit_no) ? $commercial->unit_no : 0);
                        }

                        $total_commercial_unit_extra = 0;
                        if ($commercialExtra = $file->commercialExtra) {
                            $total_commercial_unit_extra = (!empty($commercialExtra->unit_no) ? $commercialExtra->unit_no : 0);
                        }

                        Arr::set($result[$file->id], 'Total Commercial Unit', $total_commercial_unit + $total_commercial_unit_extra);

                        // total unit
                        $total_unit = $total_residential_unit + $total_residential_unit_extra + $total_commercial_unit + $total_commercial_unit_extra;

                        Arr::set($result[$file->id], 'Total Unit', $total_unit);

                        // total floor
                        $total_floor = 0;
                        if ($strata = $file->strata) {
                            $total_floor = (!empty($strata->total_floor) ? $strata->total_floor : 0);
                        }

                        Arr::set($result[$file->id], 'Total Floor', $total_floor);
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Electricity_' . strtoupper($cob));
    }

    public function uploadOCR($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $meetings = $file->meetingDocument;
                        if ($meetings->count() > 0) {
                            foreach ($meetings as $meeting) {
                                $ocrs = $meeting->ocrs;
                                if ($ocrs->count() > 0) {
                                    $result[$meeting->id] = [];

                                    Arr::set($result[$meeting->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                                    Arr::set($result[$meeting->id], 'File No', $file->file_no);
                                    Arr::set($result[$meeting->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                                    Arr::set($result[$meeting->id], 'AGM Date', ($meeting->agm_date && $meeting->agm_date != '0000-00-00' ? $meeting->agm_date : ''));

                                    $notice_agm_egm = '';
                                    $minutes_agm_egm = '';
                                    $minutes_ajk = '';
                                    $ajk_info = '';
                                    $report_audited_financial = '';
                                    $house_rules = '';

                                    foreach ($ocrs as $ocr) {
                                        if ($ocr->type == 'notice_agm_egm' && !empty($ocr->url)) {
                                            $notice_agm_egm = 'Uploaded';
                                        }
                                        if ($ocr->type == 'minutes_agm_egm' && !empty($ocr->url)) {
                                            $minutes_agm_egm = 'Uploaded';
                                        }
                                        if ($ocr->type == 'minutes_ajk' && !empty($ocr->url)) {
                                            $minutes_ajk = 'Uploaded';
                                        }
                                        if ($ocr->type == 'ajk_info' && !empty($ocr->url)) {
                                            $ajk_info = 'Uploaded';
                                        }
                                        if ($ocr->type == 'report_audited_financial' && !empty($ocr->url)) {
                                            $report_audited_financial = 'Uploaded';
                                        }
                                        if ($ocr->type == 'house_rules' && !empty($ocr->url)) {
                                            $house_rules = 'Uploaded';
                                        }
                                    }

                                    Arr::set($result[$meeting->id], 'Salinan notis AGM/EGM OCR', $notice_agm_egm);
                                    Arr::set($result[$meeting->id], 'Salinan minit AGM/EGM OCR', $minutes_agm_egm);
                                    Arr::set($result[$meeting->id], 'Salinan minit mesyuarat 1st JMC OCR', $minutes_ajk);
                                    Arr::set($result[$meeting->id], 'Maklumat Anggota Jawatankuasa (Lampiran A) OCR', $ajk_info);
                                    Arr::set($result[$meeting->id], 'Laporan Akaun Teraudit OCR', $report_audited_financial);
                                    Arr::set($result[$meeting->id], 'Salinan kaedah-kaedah dalam yang diluluskan (House Rules) OCR', $house_rules);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Upload_OCR_' . strtoupper($cob));
    }

    public function commercial($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        if ($file->commercial) {
                            $result[$file->id] = [];

                            Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                            Arr::set($result[$file->id], 'File No', $file->file_no);
                            Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                            $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                            if ($designations) {
                                foreach ($designations as $designation) {
                                    $ajk_detail = AJKDetails::where('file_id', $file->id)
                                        ->where('designation', $designation->id)
                                        ->where('is_deleted', 0)
                                        ->orderBy('start_year', 'desc')
                                        ->orderBy('month', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                    Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                    Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                                }
                            }

                            // total residential unit
                            $total_residential_unit = 0;
                            if ($resident = $file->resident) {
                                $total_residential_unit = (!empty($resident->unit_no) ? $resident->unit_no : 0);
                            }

                            $total_residential_unit_extra = 0;
                            if ($residentExtra = $file->residentExtra) {
                                $total_residential_unit_extra = (!empty($residentExtra->unit_no) ? $residentExtra->unit_no : 0);
                            }

                            Arr::set($result[$file->id], 'Total Residential Unit', $total_residential_unit + $total_residential_unit_extra);

                            // total commercial unit
                            $total_commercial_unit = 0;
                            if ($commercial = $file->commercial) {
                                $total_commercial_unit = (!empty($commercial->unit_no) ? $commercial->unit_no : 0);
                            }

                            $total_commercial_unit_extra = 0;
                            if ($commercialExtra = $file->commercialExtra) {
                                $total_commercial_unit_extra = (!empty($commercialExtra->unit_no) ? $commercialExtra->unit_no : 0);
                            }

                            Arr::set($result[$file->id], 'Total Commercial Unit', $total_commercial_unit + $total_commercial_unit_extra);

                            // total unit
                            $total_unit = $total_residential_unit + $total_residential_unit_extra + $total_commercial_unit + $total_commercial_unit_extra;

                            Arr::set($result[$file->id], 'Total Unit', $total_unit);
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Commercial_' . strtoupper($cob));
    }

    public function extractData($cob = null, $year)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils && $year) {
            foreach ($councils as $council) {
                foreach ($council->files as $file) {
                    $result[$file->id] = [];

                    Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                    Arr::set($result[$file->id], 'File No', $file->file_no);
                    Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                    Arr::set($result[$file->id], 'Year', $year);

                    /**
                     * Finance
                     */
                    for ($month = 1; $month <= 12; $month++) {
                        $finance = DB::table('finance_file')
                            ->join('files', 'finance_file.file_id', '=', 'files.id')
                            ->where('finance_file.month', $month)
                            ->where('finance_file.year', $year)
                            ->where('files.company_id', $council->id)
                            ->where('files.id', $file->id)
                            ->where('files.is_deleted', 0)
                            ->where('finance_file.company_id', $council->id)
                            ->where('finance_file.is_deleted', 0)
                            ->count();

                        $dateObj = DateTime::createFromFormat('!m', $month);
                        $monthName = $dateObj->format('F');
                        Arr::set($result[$file->id], trans('Finance') . ' ' . $monthName . ' ' . $year, $finance ? 'Yes' : '');
                    }

                    /**
                     * AGM
                     */
                    $agm = DB::table('meeting_document')
                        ->join('files', 'meeting_document.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->whereYear('meeting_document.agm_date', '=', $year)
                        ->where('meeting_document.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('AGM') . ' ' . $year, $agm ? 'Yes' : '');

                    /**
                     * AJK
                     */
                    $ajk = DB::table('ajk_details')
                        ->join('files', 'ajk_details.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->where('ajk_details.start_year', $year)
                        ->where('ajk_details.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('AJK') . ' ' . $year, $ajk ? 'Yes' : '');

                    /**
                     * Document
                     */
                    $document = DB::table('document')
                        ->join('files', 'document.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->whereYear('document.created_at', '=', $year)
                        ->where('document.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('Document') . ' ' . $year, $document ? 'Yes' : '');
                
                }
            }
        }

        return $this->result($result, $filename = 'Extract_Data_' . $year . '_' . strtoupper($cob));
    }

    public function agmHasBeenApproved($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {

                        $meetingDocuments = DB::table('files')
                            ->select(
                                'meeting_document.*',
                                'meeting_document_statuses.user_id as approved_by',
                                'meeting_document_statuses.endorsed_by as endorsed_by',
                                'meeting_document_statuses.endorsed_email as endorsed_email',
                                'meeting_document_statuses.created_at as endorsed_date'
                            )
                            ->join('meeting_document', 'files.id', '=', 'meeting_document.file_id')
                            ->join('meeting_document_statuses', 'meeting_document.id', '=', 'meeting_document_statuses.meeting_document_id')
                            ->where('files.id', $file->id)
                            ->where('files.is_deleted', false)
                            ->where('meeting_document.is_deleted', false)
                            ->where('meeting_document_statuses.status', 'approved')
                            ->where('meeting_document_statuses.is_deleted', false)
                            ->orderBy('meeting_document.agm_date')
                            ->get();

                        if ($meetingDocuments) {
                            foreach ($meetingDocuments as $meetingDocument) {
                                $result[$meetingDocument->id] = [];

                                $approver = User::find($meetingDocument->approved_by);

                                Arr::set($result[$meetingDocument->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                                Arr::set($result[$meetingDocument->id], 'File No', $file->file_no);
                                Arr::set($result[$meetingDocument->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                                Arr::set($result[$meetingDocument->id], 'AGM Date', (!empty($meetingDocument->agm_date) ? $meetingDocument->agm_date : ''));
                                Arr::set($result[$meetingDocument->id], 'Endorsed By', (!empty($meetingDocument->endorsed_by) ? $meetingDocument->endorsed_by : ''));
                                Arr::set($result[$meetingDocument->id], 'Endorsed E-mail', (!empty($meetingDocument->endorsed_email) ? $meetingDocument->endorsed_email : ''));
                                Arr::set($result[$meetingDocument->id], 'Approved By', ($approver ? $approver->full_name : ''));
                                Arr::set($result[$meetingDocument->id], 'Approved Date', (!empty($meetingDocument->endorsed_date) ? $meetingDocument->endorsed_date : ''));
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'AGM_Has_Been_Approved_' . strtoupper($cob));
    }

    public function exportOwner($cob = null, $category = 'all', $page = 'all')
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $query = Buyer::select(
                        'buyer.*',
                        'files.file_no as file_no',
                        'company.short_name as company_name',
                        'strata.name as strata_name',
                        'category.description as category_name'
                    )
                    ->join('files', 'files.id', '=', 'buyer.file_id')
                    ->join('company', 'company.id', '=', 'files.company_id')
                    ->join('strata', 'strata.file_id', '=', 'files.id')
                    ->join('category', 'category.id', '=', 'strata.category')
                    ->where('buyer.is_deleted', false)
                    ->where('files.is_deleted', false)
                    ->where('company.is_deleted', false)
                    ->where('category.is_deleted', false)
                    ->where('company.id', $council->id)
                    ->orderBy('category.description')
                    ->orderBy('files.file_no');

                if (!empty($category) && $category != 'all') {
                    $query->where('category.description', $category);
                }

                if ($page != 'all') {
                    $limit = 3000;

                    if ($page == 1) {
                        $skip = 0 * $limit;
                    } else if ($page == 1) {
                        $skip = 1 * $limit;
                    } else if ($page == 2) {
                        $skip = 2 * $limit;
                    } else if ($page == 3) {
                        $skip = 3 * $limit;
                    } else if ($page == 4) {
                        $skip = 4 * $limit;
                    } else if ($page == 5) {
                        $skip = 5 * $limit;
                    } else if ($page == 6) {
                        $skip = 6 * $limit;
                    } else if ($page == 7) {
                        $skip = 7 * $limit;
                    } else if ($page == 8) {
                        $skip = 8 * $limit;
                    } else if ($page == 9) {
                        $skip = 9 * $limit;
                    } else if ($page == 10) {
                        $skip = 10 * $limit;
                    } else {
                        $skip = 0 * $limit;
                    }

                    $query->skip($skip)->take($limit);
                }

                $owners = $query->get();

                if ($owners) {
                    foreach ($owners as $owner) {
                        Arr::set($result[$owner->id], 'Council', (!empty($owner->company_name) ? $owner->company_name : ''));
                        Arr::set($result[$owner->id], 'File No', (!empty($owner->file_no) ? $owner->file_no : ''));
                        Arr::set($result[$owner->id], 'Strata Name', (!empty($owner->strata_name) ? $owner->strata_name : ''));
                        Arr::set($result[$owner->id], 'Category', (!empty($owner->category_name) ? $owner->category_name : ''));
                        Arr::set($result[$owner->id], 'Unit No', (!empty($owner->unit_no) ? $owner->unit_no : ''));
                        Arr::set($result[$owner->id], 'No Petak', (!empty($owner->no_petak) ? $owner->no_petak : ''));
                        Arr::set($result[$owner->id], 'No Petak Aksesori ', (!empty($owner->no_petak_aksesori) ? $owner->no_petak_aksesori : ''));
                        Arr::set($result[$owner->id], 'Keluasan Lantai Petak', (!empty($owner->keluasan_lantai_petak) ? $owner->keluasan_lantai_petak : ''));
                        Arr::set($result[$owner->id], 'Keluasan Lantai Petak Aksesori', (!empty($owner->keluasan_lantai_petak_aksesori) ? $owner->keluasan_lantai_petak_aksesori : ''));
                        Arr::set($result[$owner->id], 'Unit Share', (!empty($owner->unit_share) ? $owner->unit_share : ''));
                        Arr::set($result[$owner->id], 'Jenis Kegunaan', (!empty($owner->jenis_kegunaan) ? $owner->jenis_kegunaan : ''));
                        Arr::set($result[$owner->id], 'Owner Name', (!empty($owner->owner_name) ? $owner->owner_name : ''));
                        Arr::set($result[$owner->id], 'Owner IC No', (!empty($owner->ic_company_no) ? $owner->ic_company_no : ''));
                        Arr::set($result[$owner->id], 'Owner Phone No', (!empty($owner->phone_no) ? $owner->phone_no : ''));
                        Arr::set($result[$owner->id], 'Owner E-mail', (!empty($owner->email) ? $owner->email : ''));
                        Arr::set($result[$owner->id], 'Owner Race', ($owner->race ? $owner->race->name_en : ''));
                        Arr::set($result[$owner->id], 'Owner Address', (!empty($owner->address) ? $owner->address : ''));
                        Arr::set($result[$owner->id], 'Owner Alamat Surat Menyurat', (!empty($owner->alamat_surat_menyurat) ? $owner->alamat_surat_menyurat : ''));
                        Arr::set($result[$owner->id], 'Owner Nationality', ($owner->nationality ? $owner->nationality->name : ''));
                        Arr::set($result[$owner->id], 'Caj Penyelenggaraan (RM)', (!empty($owner->caj_penyelenggaraan) ? $owner->caj_penyelenggaraan : ''));
                        Arr::set($result[$owner->id], 'Sinking Fund (RM)', (!empty($owner->sinking_fund) ? $owner->sinking_fund : ''));
                        Arr::set($result[$owner->id], 'Owner 2 Name', (!empty($owner->nama2) ? $owner->nama2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 IC No', (!empty($owner->ic_no2) ? $owner->ic_no2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 Phone No', (!empty($owner->phone_no2) ? $owner->phone_no2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 E-mail', (!empty($owner->email2) ? $owner->email2 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 Name', (!empty($owner->nama3) ? $owner->nama3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 IC No', (!empty($owner->ic_no3) ? $owner->ic_no3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 Phone No', (!empty($owner->phone_no3) ? $owner->phone_no3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 E-mail', (!empty($owner->email3) ? $owner->email3 : ''));
                        Arr::set($result[$owner->id], 'Lawyer Name', (!empty($owner->lawyer_name) ? $owner->lawyer_name : ''));
                        Arr::set($result[$owner->id], 'Lawyer Address', (!empty($owner->lawyer_address) ? $owner->lawyer_address : ''));
                        Arr::set($result[$owner->id], 'Lawyer Fail Ref No', (!empty($owner->lawyer_fail_ref_no) ? $owner->lawyer_fail_ref_no : ''));
                        Arr::set($result[$owner->id], 'Remarks', (!empty($owner->remarks) ? $owner->remarks : ''));
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Owner_' . strtoupper($cob) . '_Page_' . $page);
    }

    public function activeStrata($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->activeFiles) {
                    foreach ($council->activeFiles as $file) {
                        $result[$file->id] = [];

                        Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                        Arr::set($result[$file->id], 'File No', $file->file_no);
                        Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                        $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                        if ($designations) {
                            foreach ($designations as $designation) {
                                $ajk_detail = AJKDetails::where('file_id', $file->id)
                                    ->where('designation', $designation->id)
                                    ->where('is_deleted', 0)
                                    ->orderBy('start_year', 'desc')
                                    ->orderBy('month', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                            }
                        }

                        $pic_name = '';
                        $pic_phone_no = '';
                        $pic_email = '';
                        
                        if ($file->personInCharge) {
                            foreach ($file->personInCharge as $pic) {
                                if ($pic->user->full_name) {
                                    $pic_name = $pic->user->full_name;
                                }
                                if ($pic->user->phone_no) {
                                    $pic_phone_no = $pic->user->phone_no;
                                }
                                if ($pic->user->email) {
                                    $pic_email = $pic->user->email;
                                }
                            }
                        }

                        Arr::set($result[$file->id], 'PIC Name', $pic_name);
                        Arr::set($result[$file->id], 'PIC Phone No', $pic_phone_no);
                        Arr::set($result[$file->id], 'PIC E-mail', $pic_email);
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Active_Strata_' . strtoupper($cob));
    }
}
