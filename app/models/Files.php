<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Files extends Eloquent {

    protected $table = 'files';

    public function jmb() {
        return $this->hasOne('User', 'file_id');
    }

    public function owner() {
        return $this->hasMany('Buyer', 'file_id');
    }

    public function tenant() {
        return $this->hasMany('Tenant', 'file_id');
    }

    public function strata() {
        return $this->hasOne('Strata', 'file_id');
    }

    public function houseScheme() {
        return $this->hasOne('HouseScheme', 'file_id');
    }

    public function finance() {
        return $this->hasMany('Finance', 'file_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function resident() {
        return $this->hasOne('Residential', 'file_id')->latest();
    }

    public function commercial() {
        return $this->hasOne('Commercial', 'file_id')->latest();
    }

    public function facility() {
        return $this->hasOne('Facility', 'file_id');
    }

    public function other() {
        return $this->hasOne('OtherDetails', 'file_id');
    }

    public function financeSupport() {
        return $this->hasMany('FinanceSupport', 'file_id');
    }

    public function meetingDocument() {
        return $this->hasMany('MeetingDocument', 'file_id');
    }

    public function latestMeetingDocument() {
        return $this->hasOne('MeetingDocument', 'file_id')->latest();
    }

    public function insurance() {
        return $this->hasMany('Insurance', 'file_id');
    }

    public function defect() {
        return $this->hasMany('Defect', 'file_id');
    }

    public function buyer() {
        return $this->hasMany('Buyer', 'file_id');
    }

    public function management() {
        return $this->hasOne('Management', 'file_id');
    }

    public function managementDeveloper() {
        return $this->hasOne('ManagementDeveloper', 'file_id');
    }

    public function managementJMB() {
        return $this->hasOne('ManagementJMB', 'file_id');
    }

    public function managementMC() {
        return $this->hasOne('ManagementMC', 'file_id');
    }

    public function managementAgent() {
        return $this->hasOne('ManagementAgent', 'file_id');
    }

    public function managementOthers() {
        return $this->hasOne('ManagementOthers', 'file_id');
    }

    public function ratings() {
        return $this->hasOne('Scoring', 'file_id');
    }

    public function monitoring() {
        return $this->hasOne('Monitoring', 'file_id');
    }

    public function ajk_details() {
        return $this->hasMany('AJKDetails', 'file_id');
    }

    public function personInCharge() {
        return $this->hasMany('HousingSchemeUser', 'file_id');
    }

    public function scopeFile($query) {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('files.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('files.company_id', Session::get('admin_cob'));
            }
        }
        return $query->where('files.is_deleted', 0);
    }

    public static function getInsuranceReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        $provider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
        if (count($company) > 0 && count($provider) > 0) {
            foreach ($company as $cob) {
                foreach ($provider as $pro) {
                    $insurance = DB::table('insurance')
                            ->join('files', 'insurance.file_id', '=', 'files.id')
                            ->where('files.company_id', $cob->id)
                            ->where('insurance.insurance_provider_id', $pro->id)
                            ->where('files.is_deleted', 0)
                            ->where('insurance.is_deleted', 0)
                            ->count();

                    $dataPro[$pro->id] = array(
                        'id' => $pro->id,
                        'provider' => $pro->name,
                        'total' => $insurance
                    );
                }

                $result[] = array(
                    'company' => $cob,
                    'provider' => $dataPro,
                );
            }
        }

        return $result;
    }

    public static function getComplaintReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        $category = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
        if (count($company) > 0 && count($category) > 0) {
            foreach ($company as $cob) {
                foreach ($category as $cat) {
                    $defect = DB::table('defect')
                            ->join('files', 'defect.file_id', '=', 'files.id')
                            ->where('files.company_id', $cob->id)
                            ->where('defect.defect_category_id', $cat->id)
                            ->where('files.is_deleted', 0)
                            ->where('defect.is_deleted', 0)
                            ->count();

                    $dataCat[$cat->id] = array(
                        'id' => $cat->id,
                        'category' => $cat->name,
                        'total' => $defect
                    );
                }

                $result[] = array(
                    'company' => $cob,
                    'category' => $dataCat,
                );
            }
        }

        return $result;
    }

    public static function getCollectionReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        if ($company) {
            foreach ($company as $cob) {
                $zone_biru = 0;
                $zone_kuning = 0;
                $zone_merah = 0;

                $file = Files::where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->get();

                if ($file) {
                    foreach ($file as $files) {
                        if ($files->finance) {
                            foreach ($files->finance as $finance) {
                                $berjaya_dikutip = 0;
                                $sepatut_dikutip = 0;
                                $purata_dikutip = 0;

                                if ($finance->year == date('Y')) {
                                    if ($finance->financeIncome) {
                                        foreach ($finance->financeReport as $report) {
                                            if ($report->type == 'SF') {
                                                $sepatut_dikutip = $sepatut_dikutip + $report->fee_semasa;
                                            }
                                        }
                                        foreach ($finance->financeIncome as $income) {
                                            if ($income->name == 'SINKING FUND') {
                                                $berjaya_dikutip = $berjaya_dikutip + $income->semasa;
                                            }
                                        }
                                    }
                                }

                                if (!empty($berjaya_dikutip) && !empty($sepatut_dikutip)) {
                                    $purata_dikutip = round(($berjaya_dikutip / $sepatut_dikutip) * 100, 2);
                                }

                                if ($purata_dikutip >= 80) {
                                    $zone_biru = $zone_biru + 1;
                                } else if ($purata_dikutip < 79 && $purata_dikutip >= 50) {
                                    $zone_kuning = $zone_kuning + 1;
                                } else {
                                    $zone_merah = $zone_merah + 1;
                                }
                            }
                        }
                    }
                }

                $result[] = array(
                    'cob_id' => $cob_id,
                    'company_id' => $cob->id,
                    'company_name' => $cob->name,
                    'zon_biru' => $zone_biru,
                    'zon_kuning' => $zone_kuning,
                    'zon_merah' => $zone_merah
                );
            }
        }

        return $result;
    }

    public static function getCouncilReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        if ($company) {
            foreach ($company as $cob) {
                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_buyer = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('buyer.is_deleted', 0)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('tenant.is_deleted', 0)
                        ->where('files.is_deleted', 0)
                        ->count();

                $result[] = array(
                    'company' => $cob,
                    'total_strata' => $total_strata,
                    'total_jmb' => $total_jmb,
                    'total_mc' => $total_mc,
                    'total_buyer' => $total_buyer,
                    'total_tenant' => $total_tenant
                );
            }
        }

        return $result;
    }

    public static function getDunReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        $duns = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        if ($company && $duns) {
            foreach ($company as $cob) {
                $dataDun = [];
                foreach ($duns as $dun) {
                    $total_file = DB::table('strata')
                            ->join('files', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', $cob->id)
                            ->where('strata.dun', $dun->id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $dataCat = [];
                    if ($total_file) {
                        foreach ($category as $cat) {
                            $total_cat_file = DB::table('strata')
                                    ->join('files', 'strata.file_id', '=', 'files.id')
                                    ->where('files.company_id', $cob->id)
                                    ->where('strata.dun', $dun->id)
                                    ->where('strata.category', $cat->id)
                                    ->where('files.is_deleted', 0)
                                    ->count();

                            $dataCat[$cat->id] = array(
                                'id' => $cat->id,
                                'name' => $cat->description,
                                'total' => $total_cat_file,
                            );
                        }

                        $dataDun[$dun->id] = array(
                            'id' => $dun->id,
                            'name' => $dun->description,
                            'total' => $total_file,
                            'category' => $dataCat
                        );
                    }
                }

                $result[] = array(
                    'id' => $cob->id,
                    'company' => $cob,
                    'dun' => $dataDun,
                );
            }
        }

        return $result;
    }

    public static function getParlimentReportByCOB($cob_id = NULL) {
        $result = array();

        if ($cob_id) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }

        $parliments = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        if ($company && $parliments) {
            foreach ($company as $cob) {
                $dataParliment = [];
                foreach ($parliments as $parliment) {
                    $total_file = DB::table('strata')
                            ->join('files', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', $cob->id)
                            ->where('strata.parliament', $parliment->id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $dataCat = [];
                    if ($total_file) {
                        foreach ($category as $cat) {
                            $total_cat_file = DB::table('strata')
                                    ->join('files', 'strata.file_id', '=', 'files.id')
                                    ->where('files.company_id', $cob->id)
                                    ->where('strata.parliament', $parliment->id)
                                    ->where('strata.category', $cat->id)
                                    ->where('files.is_deleted', 0)
                                    ->count();

                            $dataCat[$cat->id] = array(
                                'id' => $cat->id,
                                'name' => $cat->description,
                                'total' => $total_cat_file,
                            );
                        }

                        $dataParliment[$parliment->id] = array(
                            'id' => $parliment->id,
                            'name' => $parliment->description,
                            'total' => $total_file,
                            'category' => $dataCat
                        );
                    }
                }

                $result[] = array(
                    'id' => $cob->id,
                    'company' => $cob,
                    'parliment' => $dataParliment,
                );
            }
        }

        return $result;
    }

    public static function getVPYear() {
        $min_year = Files::where('year', '>', 0)->min('year');
        $max_year = date('Y');

        $year = array('' => trans('app.forms.please_select'));
        for ($max_year; $max_year >= $min_year; $max_year--) {
            $year += array($max_year => $max_year);
        }

        return $year;
    }

    public static function getVPReport($cob_id = NULL, $year = NULL) {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if ($cob_id && $year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($cob_id) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.is_deleted', 0)
                            ->count();
                }
            } else {
                if ($cob_id && $year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($cob_id) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 1)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('files.is_active', 2)
                            ->where('files.is_deleted', 0)
                            ->count();
                }
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                if ($cob_id && $year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($cob_id) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 1)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 2)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 1)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_active', 2)
                            ->where('files.is_deleted', 0)
                            ->count();
                }
            } else {
                if ($cob_id && $year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($cob_id) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 1)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 2)
                            ->where('files.company_id', $cob_id)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else if ($year) {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 1)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 2)
                            ->where('strata.year', $year)
                            ->where('files.is_deleted', 0)
                            ->count();
                } else {
                    $file_after_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 1)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $file_before_vp = DB::table('files')
                            ->join('strata', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', 1)
                            ->where('files.is_deleted', 0)
                            ->count();
                }
            }
        }

        $result = array(
            'cob_id' => $cob_id,
            'year' => $year,
            'total_after_vp' => $file_after_vp,
            'total_before_vp' => $file_before_vp,
        );

        return $result;
    }

    public static function getAnalyticData() {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $total_files = Files::where('is_deleted', 0)
                            ->where('status', 1)
                            ->count();
        $total_finance_file = DB::table('finance_file')
                                ->join('files','finance_file.file_id','=','files.id')
                                ->where('finance_file.is_active', 1)
                                ->where($active)
                                ->count();
        $total_finance_support = DB::table('finance_support')
                                    ->join('files','finance_support.file_id','=','files.id')
                                    ->where('finance_support.is_active', 1)
                                    ->where($active)
                                    ->count();
        $total_insurance_provider = DB::table('insurance_provider')
                                        ->where('is_active', 1)
                                        ->where('is_deleted', 0)
                                        ->count();
        $files_summary = DB::table('files')
                            ->where('is_deleted', 0)
                            ->where('status', 1)
                            ->selectRaw('year, count(id) as total')
                            ->groupBy('year')
                            ->get();
        $file_history_name = [];
        $file_history = [];
        foreach($files_summary as $summary) {
            $year = "Year ". $summary->year;
            if($summary->year == '0') {
                $year = 'No Records';
            }
            array_push($file_history_name, [$year]);
            array_push($file_history, [$summary->total]);
        }

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {

                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            } else {
                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where($active)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            } else {
                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            }
        }

        $all_summary = array(
            ['name' => 'Files', 'slug' => 'file', 'y' => $total_files],
            ['name' => 'Finance Files', 'slug' => 'finance_file', 'y' => $total_finance_file],
            ['name' => 'Finance Supports', 'slug' => 'finance_support', 'y' => $total_finance_support],
            ['name' => 'Insurance Providers', 'slug' => 'insurance_provider', 'y' => $total_insurance_provider],
            
        );
        
        $result = array(
            'summary' => $all_summary,
            'file_history_name' => $file_history_name,
            'file_history' => $file_history,
            'total_strata' => $total_strata,
            'total_jmb' => $total_jmb,
            'total_mc' => $total_mc,
            'total_owner' => $total_owner,
            'total_tenant' => $total_tenant,
        );

        return $result;
    }

    public static function getDashboardData() {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $condition5 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 90)->where('scoring_quality_index.total_score', '<=', 100);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $condition4 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 70)->where('scoring_quality_index.total_score', '<=', 89);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $condition3 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 50)->where('scoring_quality_index.total_score', '<=', 69);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $condition2 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 40)->where('scoring_quality_index.total_score', '<=', 49);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $condition1 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 0)->where('scoring_quality_index.total_score', '<=', 39);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.id', Auth::user()->file_id)
                //         ->where('files.company_id', Auth::user()->company_id)
                //         ->where('developer.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 0)
                //         ->where($active)
                //         ->count();

                // $total_liquidator = DB::table('liquidators')
                //         ->join('house_scheme', 'liquidators.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.id', Auth::user()->file_id)
                //         ->where('files.company_id', Auth::user()->company_id)
                //         ->where('liquidators.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 1)
                //         ->where($active)
                //         ->count();
                $total_developer = DB::table('house_scheme')
                                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                    ->where('files.id', Auth::user()->file_id)
                                    ->where('files.company_id', Auth::user()->company_id)
                                    ->where('house_scheme.is_deleted', 0)
                                    ->where('house_scheme.is_liquidator', 0)
                                    ->where($active)
                                    ->count();
                $total_liquidator = DB::table('house_scheme')
                                        ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                        ->where('files.id', Auth::user()->file_id)
                                        ->where('files.company_id', Auth::user()->company_id)
                                        ->where('house_scheme.is_deleted', 0)
                                        ->where('house_scheme.is_liquidator', 1)
                                        ->where($active)
                                        ->count();

                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_agent = DB::table('management_agent')
                        ->join('files', 'management_agent.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_others = DB::table('management_others')
                        ->join('files', 'management_others.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $fiveStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition5)
                        ->count();

                $fourStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition4)
                        ->count();

                $threeStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition3)
                        ->count();

                $twoStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition2)
                        ->count();

                $oneStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition1)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_rating = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();
            } else {
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', Auth::user()->company_id)
                //         ->where('developer.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 0)
                //         ->where($active)
                //         ->count();

                // $total_liquidator = DB::table('liquidators')
                //         ->join('house_scheme', 'liquidators.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', Auth::user()->company_id)
                //         ->where('liquidators.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 1)
                //         ->where($active)
                //         ->count();
                $total_developer = DB::table('house_scheme')
                                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                    ->where('files.company_id', Auth::user()->company_id)
                                    ->where('house_scheme.is_deleted', 0)
                                    ->where('house_scheme.is_liquidator', 0)
                                    ->where($active)
                                    ->count();
                $total_liquidator = DB::table('house_scheme')
                                        ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                        ->where('files.company_id', Auth::user()->company_id)
                                        ->where('house_scheme.is_deleted', 0)
                                        ->where('house_scheme.is_liquidator', 1)
                                        ->where($active)
                                        ->count();

                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_agent = DB::table('management_agent')
                        ->join('files', 'management_agent.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_others = DB::table('management_others')
                        ->join('files', 'management_others.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $fiveStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition5)
                        ->count();

                $fourStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition4)
                        ->count();

                $threeStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition3)
                        ->count();

                $twoStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition2)
                        ->count();

                $oneStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition1)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_rating = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($active)
                        ->count();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('developer.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 0)
                //         ->where($active)
                //         ->count();
                        
                // $total_liquidator = DB::table('liquidators')
                //         ->join('house_scheme', 'liquidators.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('liquidators.is_deleted', 0)
                //         // ->where('house_scheme.is_liquidator', 1)
                //         // ->where($active)
                //         ->count();
                $total_developer = DB::table('house_scheme')
                                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                    ->where('house_scheme.is_deleted', 0)
                                    ->where('house_scheme.is_liquidator', 0)
                                    ->where($active)
                                    ->count();
                $total_liquidator = DB::table('house_scheme')
                                        ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                        ->where('house_scheme.is_deleted', 0)
                                        ->where('house_scheme.is_liquidator', 1)
                                        ->where($active)
                                        ->count();
                                        
                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_agent = DB::table('management_agent')
                        ->join('files', 'management_agent.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_others = DB::table('management_others')
                        ->join('files', 'management_others.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $fiveStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($condition5)
                        ->count();

                $fourStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($condition4)
                        ->count();

                $threeStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($condition3)
                        ->count();

                $twoStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($condition2)
                        ->count();

                $oneStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($condition1)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_rating = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where($active)
                        ->count();
            } else {
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', Session::get('admin_cob'))
                //         ->where('developer.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 0)
                //         ->where($active)
                //         ->count();
                
                // $total_liquidator = DB::table('liquidators')
                //         ->join('house_scheme', 'liquidators.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', Session::get('admin_cob'))
                //         ->where('liquidators.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 1)
                //         ->where($active)
                //         ->count();
                $total_developer = DB::table('house_scheme')
                                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                    ->where('house_scheme.is_deleted', 0)
                                    ->where('house_scheme.is_liquidator', 0)
                                    ->where('files.company_id', Session::get('admin_cob'))
                                    ->where($active)
                                    ->count();
                $total_liquidator = DB::table('house_scheme')
                                        ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                        ->where('house_scheme.is_deleted', 0)
                                        ->where('house_scheme.is_liquidator', 1)
                                        ->where('files.company_id', Session::get('admin_cob'))
                                        ->where($active)
                                        ->count();

                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_agent = DB::table('management_agent')
                        ->join('files', 'management_agent.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_others = DB::table('management_others')
                        ->join('files', 'management_others.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $fiveStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($condition5)
                        ->count();

                $fourStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($condition4)
                        ->count();

                $threeStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($condition3)
                        ->count();

                $twoStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($condition2)
                        ->count();

                $oneStar = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($condition1)
                        ->count();

                $total_strata = DB::table('strata')
                        ->join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_rating = DB::table('scoring_quality_index')
                        ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_owner = DB::table('buyer')
                        ->join('files', 'buyer.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();

                $total_tenant = DB::table('tenant')
                        ->join('files', 'tenant.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where($active)
                        ->count();
            }
        }
        
        $management = array(
            ['name' => 'Developer', 'y' => $total_developer],
            ['name' => 'Liquidator', 'y' => $total_liquidator],
            ['name' => 'JMB', 'y' => $total_jmb],
            ['name' => 'MC', 'y' => $total_mc],
            ['name' => 'Agent', 'y' => $total_agent],
            ['name' => 'Others', 'y' => $total_others]
        );

        $rating = array(
            ['name' => '5 Stars', 'y' => $fiveStar],
            ['name' => '4 Stars', 'y' => $fourStar],
            ['name' => '3 Stars', 'y' => $threeStar],
            ['name' => '2 Stars', 'y' => $twoStar],
            ['name' => '1 Star', 'y' => $oneStar]
        );
        

        $result = array(
            'rating' => $rating,
            'management' => $management,
            'total_agent' => $total_agent,
            'total_developer' => $total_developer,
            'total_liquidator' => $total_liquidator,
            'total_strata' => $total_strata,
            'total_jmb' => $total_jmb,
            'total_mc' => $total_mc,
            'total_owner' => $total_owner,
            'total_tenant' => $total_tenant,
            'total_rating' => $total_rating
        );

        return $result;
    }

    public static function getStrataProfileAnalytic($request = []) {
        $query = DB::table('files')
                ->join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->join('parliment', 'strata.parliament', '=', 'parliment.id')
                ->join('finance_file', 'files.id', '=', 'finance_file.file_id')
                ->join('finance_file_income', 'finance_file.id', '=', 'finance_file_income.finance_file_id')
                ->join('finance_file_report', 'finance_file.id', '=', 'finance_file_report.finance_file_id')
                ->selectRaw('files.id, company.short_name, round((SUM(finance_file_income.semasa) / SUM(finance_file_report.fee_semasa)) * 100) as percentage');
                // ->select(DB::raw('files.*, strata.name as strata_name, company.short_name as company_name, parliment.description as parliment_name, finance_file.id as finance_id, finance_file_report.id as finance_report_id, finance_file_income.id as finance_income_id, SUM(finance_file_report.fee_semasa) as sepatut_dikutip, SUM(finance_file_income.semasa) as berjaya_dikutip, round((SUM(finance_file_income.semasa) / SUM(finance_file_report.fee_semasa)) * 100) as percentage'));
        
        // $query2 = Company::where('is_deleted', 0)->where('is_active', 1)->where('short_name','!=','LPHS');
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('files.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('files.company_id', Session::get('admin_cob'));
            }
        }
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
            // $query2 = $query2->where('short_name',$request['cob']);
        }
        $items = $query->where('finance_file_report.type', 'SF')
                        ->where('finance_file_income.name', 'SINKING FUND')
                        ->where('finance_file.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0)
                        ->groupBy('files.id')
                        ->groupBy('finance_file.id')
                        ->orderBy('files.id')
                        ->get();
        // $councils = $query2->get();
        $pie_data = [
            'Biru' => 0,
            'Kuning' => 0,
            'Merah' => 0
        ];
        // foreach($councils as $council) {
        //     $search_by_council = array_where($items, function($key1, $val1) use($council) {
        //         return $val1->short_name == $council->short_name;
        //     });
        //     $new_data = [
        //         'name' => $council->short_name,
        //         'data' => [0,0,0] // Biru, Kuning, Merah 
        //     ];

        //     foreach($search_by_council as $item) {
        //         if ($item->percentage >= 80) {
        //             $new_data['data'][0] += 1;
        //         } else if ($item->percentage < 79 && $item->percentage >= 50) {
        //             $new_data['data'][1] += 1;
        //         } else {
        //             $new_data['data'][2] += 1;
        //         }
        //     }
            
        // }
        foreach($items as $item) {
            if ($item->percentage >= 80) {
                $pie_data['Biru'] += 1;
            } else if ($item->percentage < 79 && $item->percentage >= 50) {
                $pie_data['Kuning'] += 1;
            } else {
                $pie_data['Merah'] += 1;
            }

        }
        
        $data['pie_data'] = [
            ['name' => 'Biru', 'slug' => 'biru', 'y' => $pie_data['Biru']],
            ['name' => 'Kuning', 'slug' => 'kuning', 'y' => $pie_data['Kuning']],
            ['name' => 'Merah', 'slug' => 'merah', 'y' => $pie_data['Merah']],
        ];
        return $data;
    }

    public static function getFileName() {
        $filename = array();

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $filename = Strata::join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->where('strata.name', '!=', '')
                        ->orderBy('strata.name', 'asc')
                        ->get();
            } else {
                $filename = Strata::join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->where('strata.name', '!=', '')
                        ->orderBy('strata.name', 'asc')
                        ->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $filename = Strata::join('files', 'strata.file_id', '=', 'files.id')
                            ->where('files.is_deleted', 0)
                            ->where('strata.name', '!=', '')
                            ->orderBy('strata.name', 'asc')
                            ->get();
            } else {
                $filename = Strata::join('files', 'strata.file_id', '=', 'files.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_deleted', 0)
                        ->where('strata.name', '!=', '')
                        ->orderBy('strata.name', 'asc')
                        ->get();
            }
        }

        return $filename;
    }

    public static function getRatingByCategory() {
        $result = array();

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        if ($category) {
            foreach ($category as $cat) {
                $active = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                $condition5 = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('scoring_quality_index.total_score', '>=', 90)->where('scoring_quality_index.total_score', '<=', 100);
                    $query->where('scoring_quality_index.is_deleted', 0);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                $condition4 = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('scoring_quality_index.total_score', '>=', 70)->where('scoring_quality_index.total_score', '<=', 89);
                    $query->where('scoring_quality_index.is_deleted', 0);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                $condition3 = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('scoring_quality_index.total_score', '>=', 50)->where('scoring_quality_index.total_score', '<=', 69);
                    $query->where('scoring_quality_index.is_deleted', 0);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                $condition2 = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('scoring_quality_index.total_score', '>=', 40)->where('scoring_quality_index.total_score', '<=', 49);
                    $query->where('scoring_quality_index.is_deleted', 0);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                $condition1 = function ($query) use ($cat) {
                    $query->where('category.id', $cat->id);
                    $query->where('scoring_quality_index.total_score', '>=', 0)->where('scoring_quality_index.total_score', '<=', 39);
                    $query->where('scoring_quality_index.is_deleted', 0);
                    $query->where('files.is_deleted', 0);
                    $query->where('category.is_deleted', 0);
                };

                if (!Auth::user()->getAdmin()) {
                    if (!empty(Auth::user()->file_id)) {
                        $fiveStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition5)
                                ->count();

                        $fourStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition4)
                                ->count();

                        $threeStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition3)
                                ->count();

                        $twoStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition2)
                                ->count();

                        $oneStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition1)
                                ->count();

                        $total_strata = DB::table('strata')
                                ->join('files', 'strata.file_id', '=', 'files.id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($active)
                                ->count();

                        $total_rating = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($active)
                                ->count();
                    } else {
                        $fiveStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition5)
                                ->count();

                        $fourStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition4)
                                ->count();

                        $threeStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition3)
                                ->count();

                        $twoStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition2)
                                ->count();

                        $oneStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($condition1)
                                ->count();

                        $total_strata = DB::table('strata')
                                ->join('files', 'strata.file_id', '=', 'files.id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($active)
                                ->count();

                        $total_rating = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where($active)
                                ->count();
                    }
                } else {
                    if (empty(Session::get('admin_cob'))) {
                        $fiveStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($condition5)
                                ->count();

                        $fourStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($condition4)
                                ->count();

                        $threeStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($condition3)
                                ->count();

                        $twoStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($condition2)
                                ->count();

                        $oneStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($condition1)
                                ->count();

                        $total_strata = DB::table('strata')
                                ->join('files', 'strata.file_id', '=', 'files.id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($active)
                                ->count();

                        $total_rating = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where($active)
                                ->count();
                    } else {
                        $fiveStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($condition5)
                                ->count();

                        $fourStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($condition4)
                                ->count();

                        $threeStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($condition3)
                                ->count();

                        $twoStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($condition2)
                                ->count();

                        $oneStar = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($condition1)
                                ->count();

                        $total_strata = DB::table('strata')
                                ->join('files', 'strata.file_id', '=', 'files.id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($active)
                                ->count();

                        $total_rating = DB::table('scoring_quality_index')
                                ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                                ->join('strata', 'files.id', '=', 'strata.file_id')
                                ->join('category', 'strata.category', '=', 'category.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where($active)
                                ->count();
                    }
                }

                $rating = array(
                    ['name' => '5 Stars', 'y' => $fiveStar],
                    ['name' => '4 Stars', 'y' => $fourStar],
                    ['name' => '3 Stars', 'y' => $threeStar],
                    ['name' => '2 Stars', 'y' => $twoStar],
                    ['name' => '1 Star', 'y' => $oneStar]
                );

                $result[] = array(
                    'category' => $cat->description,
                    'total_strata' => $total_strata,
                    'total_rating' => $total_rating,
                    'percentage' => ($total_strata > 0 ? round(($total_rating / $total_strata) * 100, 2) : 0),
                    'no_info' => $total_strata - $total_rating,
                    'rating' => $rating
                );
            }
        }

        return $result;
    }

    public static function getManagementSummaryCOB() {
        $developer = 0;
        $liquidator = 0;
        $jmb = 0;
        $mc = 0;
        $agent = 0;
        $others = 0;
        $residential = 0;
        $commercial = 0;
        $count_less10 = 0;
        $count_more10 = 0;
        $count_all = 0;
        $sum_less10 = 0;
        $sum_more10 = 0;
        $sum_all = 0;
        $total_all = 0;

        if (!Auth::user()->getAdmin()) {
            $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        if ($company) {
            foreach ($company as $cob) {
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.developer')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', $cob->id)
                //         ->where('developer.is_deleted', 0)
                //         ->where('files.is_deleted', 0)
                //         ->groupBy('developer.id')
                //         ->get();
                // $total_developer = DB::table('developer')
                //         ->join('house_scheme', 'developer.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', $cob->id)
                //         ->where('developer.is_deleted', 0)
                //         ->where('files.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 0)
                //         ->count();

                // $total_liquidator = DB::table('liquidators')
                //         ->join('house_scheme', 'liquidators.id', '=', 'house_scheme.file_id')
                //         ->join('files', 'house_scheme.file_id', '=', 'files.id')
                //         ->where('files.company_id', $cob->id)
                //         ->where('liquidators.is_deleted', 0)
                //         ->where('files.is_deleted', 0)
                //         ->where('house_scheme.is_liquidator', 1)
                //         ->count();
                $total_developer = DB::table('house_scheme')
                                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                    ->where('files.company_id', $cob->id)
                                    ->where('files.is_deleted', 0)
                                    ->where('house_scheme.is_deleted', 0)
                                    ->where('house_scheme.is_liquidator', 0)
                                    ->count();
                $total_liquidator = DB::table('house_scheme')
                                        ->join('files', 'house_scheme.file_id', '=', 'files.id')
                                        ->where('files.company_id', $cob->id)
                                        ->where('files.is_deleted', 0)
                                        ->where('house_scheme.is_deleted', 0)
                                        ->where('house_scheme.is_liquidator', 1)
                                        ->count();
                        
                $total_jmb = DB::table('management_jmb')
                        ->join('files', 'management_jmb.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_mc = DB::table('management_mc')
                        ->join('files', 'management_mc.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_agent = DB::table('management_agent')
                        ->join('files', 'management_agent.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $total_others = DB::table('management_others')
                        ->join('files', 'management_others.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count();

                $count_residential = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('residential_block.unit_no');

                $count_residential_less10 = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('residential_block.unit_no', '<=', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('residential_block.unit_no');

                $count_residential_more10 = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('residential_block.unit_no', '>', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('residential_block.unit_no');

                $count_commercial = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('commercial_block.unit_no');

                $count_commercial_less10 = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('commercial_block.unit_no', '<=', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('commercial_block.unit_no');

                $count_commercial_more10 = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('commercial_block.unit_no', '>', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->count('commercial_block.unit_no');

                $sum_residential = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('residential_block.unit_no');

                $sum_residential_less10 = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('residential_block.unit_no', '<=', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('residential_block.unit_no');

                $sum_residential_more10 = DB::table('residential_block')
                        ->join('files', 'residential_block.file_id', '=', 'files.id')
                        ->where('residential_block.unit_no', '>', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('residential_block.unit_no');

                $sum_commercial = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('commercial_block.unit_no');

                $sum_commercial_less10 = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('commercial_block.unit_no', '<=', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('commercial_block.unit_no');

                $sum_commercial_more10 = DB::table('commercial_block')
                        ->join('files', 'commercial_block.file_id', '=', 'files.id')
                        ->where('commercial_block.unit_no', '>', 10)
                        ->where('files.company_id', $cob->id)
                        ->where('files.is_deleted', 0)
                        ->sum('commercial_block.unit_no');

                $developer = $developer + $total_developer;
                $liquidator = $liquidator + $total_liquidator;
                $jmb = $jmb + $total_jmb;
                $mc = $mc + $total_mc;
                $agent = $agent + $total_agent;
                $others = $others + $total_others;
                $residential = $residential + $sum_residential;
                $commercial = $commercial + $sum_commercial;
                $count_less10 = $count_less10 + ($count_residential_less10 + $count_commercial_less10);
                $count_more10 = $count_more10 + ($count_residential_more10 + $count_commercial_more10);
                $count_all = $count_all + ($count_residential + $count_commercial);
                $sum_less10 = $sum_less10 + ($sum_residential_less10 + $sum_commercial_less10);
                $sum_more10 = $sum_more10 + ($sum_residential_more10 + $sum_commercial_more10);
                $sum_all = $sum_all + ($sum_residential + $sum_commercial);
                $total_all = $total_all + (($total_developer) + $total_liquidator + $total_jmb + $total_mc + $total_agent + $total_others);
            }
        }
        
        $result = array(
            'developer' => $developer,
            'liquidator' => $liquidator,
            'jmb' => $jmb,
            'mc' => $mc,
            'agent' => $agent,
            'others' => $others,
            'residential' => $residential,
            'commercial' => $commercial,
            'count_less10' => $count_less10,
            'count_more10' => $count_more10,
            'count_all' => $count_all,
            'sum_less10' => $sum_less10,
            'sum_more10' => $sum_more10,
            'sum_all' => $sum_all,
            'total_all' => $total_all
        );

        return $result;
    }

    public static function getLandTitleReportByCOB($cob_id = NULL, $land_title_id = NULL) {
        $result = array();

        if (!empty($cob_id)) {
            $company = Company::where('id', $cob_id)->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                } else {
                    $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                }
            }
        }
        if (!empty($land_title_id)) {
            $category = Category::where('id', $land_title_id)->get();
        } else {
            $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        }

        if ($company) {
            foreach ($company as $cob) {
                $dataCat = [];
                foreach ($category as $cat) {
                    $total_cat_file = DB::table('strata')
                            ->join('files', 'strata.file_id', '=', 'files.id')
                            ->where('files.company_id', $cob->id)
                            ->where('strata.category', $cat->id)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $dataCat[$cat->id] = array(
                        'id' => $cat->id,
                        'name' => $cat->description,
                        'total' => $total_cat_file,
                    );
                }

                $result[] = array(
                    'id' => $cob->id,
                    'company' => $cob,
                    'category' => $dataCat
                );
            }
        }

        return $result;
    }

    public function draft() {
        return $this->hasOne('FileDrafts', 'file_id');
    }

    public function hasDraft() {
        if ($this->houseScheme->draft) {
            return true;
        } else if ($this->houseScheme->draft) {
            return true;
        } else if ($this->strata->draft) {
            return true;
        } else if ($this->management->draft) {
            return true;
        } else if ($this->other->draft) {
            return true;
        }

        return false;
    }
    
    public static function parkList() {
        $files = '';

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('park', 'strata.park', '=', 'park.id')
                        ->select(['park.*'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->orderBy('park.description')
                        ->groupBy('park.description')
                        ->lists('description', 'description');
            } else {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('park', 'strata.park', '=', 'park.id')
                        ->select(['park.*'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->orderBy('park.description')
                        ->groupBy('park.description')
                        ->lists('description', 'description');
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('park', 'strata.park', '=', 'park.id')
                        ->select(['park.*'])
                        ->where('files.is_deleted', 0)
                        ->orderBy('park.description')
                        ->groupBy('park.description')
                        ->lists('description', 'description');
            } else {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('park', 'strata.park', '=', 'park.id')
                        ->select(['park.*'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_deleted', 0)
                        ->orderBy('park.description')
                        ->groupBy('park.description')
                        ->lists('description', 'description');
            }
        }

        return $files;
    }
    
    public static function categoryList() {
        $files = '';

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('category', 'strata.category', '=', 'category.id')
                        ->select(['category.*'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->orderBy('category.description')
                        ->groupBy('category.description')
                        ->lists('description', 'description');
            } else {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('category', 'strata.category', '=', 'category.id')
                        ->select(['category.*'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->orderBy('category.description')
                        ->groupBy('category.description')
                        ->lists('description', 'description');
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('category', 'strata.category', '=', 'category.id')
                        ->select(['category.*'])
                        ->where('files.is_deleted', 0)
                        ->orderBy('category.description')
                        ->groupBy('category.description')
                        ->lists('description', 'description');
            } else {
                $files = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->join('category', 'strata.category', '=', 'category.id')
                        ->select(['category.*'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_deleted', 0)
                        ->orderBy('category.description')
                        ->groupBy('category.description')
                        ->lists('description', 'description');
            }
        }

        return $files;
    }

}
