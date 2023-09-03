<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Files extends Eloquent
{

    protected $table = 'files';

    public function jmb()
    {
        return $this->hasOne('User', 'file_id');
    }

    public function owner()
    {
        return $this->hasMany('Buyer', 'file_id');
    }

    public function tenant()
    {
        return $this->hasMany('Tenant', 'file_id');
    }

    public function strata()
    {
        return $this->hasOne('Strata', 'file_id');
    }

    public function houseScheme()
    {
        return $this->hasOne('HouseScheme', 'file_id');
    }

    public function finance()
    {
        return $this->hasMany('Finance', 'file_id');
    }

    public function financeLatest()
    {
        return $this->hasOne('Finance', 'file_id')
            ->where('finance_file.is_active', true)
            ->orderBy('finance_file.year', 'desc')
            ->orderBy('finance_file.month', 'desc');
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function resident()
    {
        return $this->hasOne('Residential', 'file_id')->latest();
    }

    public function commercial()
    {
        return $this->hasOne('Commercial', 'file_id')->latest();
    }

    public function facility()
    {
        return $this->hasOne('Facility', 'file_id');
    }

    public function other()
    {
        return $this->hasOne('OtherDetails', 'file_id');
    }

    public function financeSupport()
    {
        return $this->hasMany('FinanceSupport', 'file_id');
    }

    public function meetingDocument()
    {
        return $this->hasMany('MeetingDocument', 'file_id');
    }

    public function latestMeetingDocument()
    {
        return $this->hasOne('MeetingDocument', 'file_id')->latest();
    }

    public function latestAgmDate()
    {
        return $this->hasOne('MeetingDocument', 'file_id')->orderBy('agm_date', 'desc');
    }

    public function insurance()
    {
        return $this->hasMany('Insurance', 'file_id');
    }

    public function defect()
    {
        return $this->hasMany('Defect', 'file_id');
    }

    public function buyer()
    {
        return $this->hasMany('Buyer', 'file_id');
    }

    public function management()
    {
        return $this->hasOne('Management', 'file_id');
    }

    public function managementDevelopers()
    {
        return $this->hasMany('ManagementDeveloper', 'file_id');
    }

    public function managementJMBs()
    {
        return $this->hasMany('ManagementJMB', 'file_id');
    }

    public function managementMCs()
    {
        return $this->hasMany('ManagementMC', 'file_id');
    }

    public function managementAgents()
    {
        return $this->hasMany('ManagementAgent', 'file_id');
    }

    public function managementDeveloperLatest()
    {
        return $this->hasOne('ManagementDeveloper', 'file_id')->latest();
    }

    public function managementJMBLatest()
    {
        return $this->hasOne('ManagementJMB', 'file_id')->latest();
    }

    public function managementMCLatest()
    {
        return $this->hasOne('ManagementMC', 'file_id')->latest();
    }

    public function managementAgentLatest()
    {
        return $this->hasOne('ManagementAgent', 'file_id')->latest();
    }

    public function managementOthersLatest()
    {
        return $this->hasOne('ManagementOthers', 'file_id')->latest();
    }

    public function managementDeveloper()
    {
        return $this->hasOne('ManagementDeveloper', 'file_id');
    }

    public function managementJMB()
    {
        return $this->hasOne('ManagementJMB', 'file_id');
    }

    public function managementMC()
    {
        return $this->hasOne('ManagementMC', 'file_id');
    }

    public function managementAgent()
    {
        return $this->hasOne('ManagementAgent', 'file_id');
    }

    public function managementOthers()
    {
        return $this->hasOne('ManagementOthers', 'file_id');
    }

    public function ratings()
    {
        return $this->hasOne('Scoring', 'file_id');
    }

    public function monitoring()
    {
        return $this->hasOne('Monitoring', 'file_id');
    }

    public function ajk_details()
    {
        return $this->hasMany('AJKDetails', 'file_id');
    }

    public function personInCharge()
    {
        return $this->hasMany('HousingSchemeUser', 'file_id');
    }

    public function file_movements()
    {
        return $this->hasMany('FileMovement', 'file_id')->where('is_deleted', 0);
    }

    public function scopeFile($query)
    {
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

    public function scopeNeverHasAGM($query)
    {
        $query->file()
            ->join('company', 'files.company_id', '=', 'company.id')
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->where(function ($query) {
                $query->whereDoesntHave('meetingDocument');
                $query->orWhereHas('meetingDocument', function ($query2) {
                    $query2->where('meeting_document.agm_date', '0000-00-00');
                    $query2->where('meeting_document.is_deleted', 0);
                });
            })
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->where('company.short_name', '!=', 'MPS');

        return $query;
    }

    public static function getInsuranceReportByCOB($cob_id = NULL)
    {
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

    public static function getComplaintReportByCOB($cob_id = NULL)
    {
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

    public static function getCollectionReportByCOB($cob_id = NULL)
    {
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

    public static function getCouncilReportByCOB($cob_id = NULL)
    {
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

    public static function getDunReportByCOB($cob_id = NULL)
    {
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

    public static function getParlimentReportByCOB($cob_id = NULL)
    {
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

    public static function getVPYear()
    {
        $min_year = Files::where('year', '>', 0)->min('year');
        $max_year = date('Y');

        $year = array('' => trans('app.forms.please_select'));

        if (!empty($min_year)) {
            for ($max_year; $max_year >= $min_year; $max_year--) {
                $year += array($max_year => $max_year);
            }

            return $year;
        }

        return $year += array($max_year => $max_year);
    }

    public static function getVPReport($cob_id = NULL, $year = NULL)
    {
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

    public static function getAnalyticData()
    {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $total_files = Files::where('is_deleted', 0)
            ->where('status', 1)
            ->count();
        $total_finance_file = DB::table('finance_file')
            ->join('files', 'finance_file.file_id', '=', 'files.id')
            ->where('finance_file.is_active', 1)
            ->where($active)
            ->count();
        $total_finance_support = DB::table('finance_support')
            ->join('files', 'finance_support.file_id', '=', 'files.id')
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
        foreach ($files_summary as $summary) {
            $year = "Year " . $summary->year;
            if ($summary->year == '0') {
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
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
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
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
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
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
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
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
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

    public static function getDashboardData()
    {
        $active = function ($query) {
            $query->where('files.is_deleted', 0)
                ->where('files.is_active', '!=', 2);
            if (Auth::user()->getCOB->short_name == 'MPS') {
                $query->where('files.company_id', 8);
            }
        };

        $condition5 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 90)->where('scoring_quality_index.total_score', '<=', 100);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
            $query->where('files.is_active', '!=', 2);
        };

        $condition4 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 70)->where('scoring_quality_index.total_score', '<=', 89);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
            $query->where('files.is_active', '!=', 2);
        };

        $condition3 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 50)->where('scoring_quality_index.total_score', '<=', 69);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
            $query->where('files.is_active', '!=', 2);
        };

        $condition2 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 40)->where('scoring_quality_index.total_score', '<=', 49);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
            $query->where('files.is_active', '!=', 2);
        };

        $condition1 = function ($query) {
            $query->where('scoring_quality_index.total_score', '>=', 0)->where('scoring_quality_index.total_score', '<=', 39);
            $query->where('scoring_quality_index.is_deleted', 0);
            $query->where('files.is_deleted', 0);
            $query->where('files.is_active', '!=', 2);
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $total_developer = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('management.is_developer', 1)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();
                $total_liquidator = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 1)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_jmb = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where('management.is_jmb', 1)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_mc = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_mc', 1)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_agent = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_agent', 1)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_others = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 1)
                    ->where('management.no_management', 0)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where('management.no_management', 1)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management_1 = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('management.is_developer', 0)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $fiveStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition5)
                    ->count();

                $fourStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition4)
                    ->count();

                $threeStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition3)
                    ->count();

                $twoStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition2)
                    ->count();

                $oneStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition1)
                    ->count();

                $total_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->count();

                $total_active_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', true)
                    ->where($active)
                    ->count();

                $total_inactive_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', '!=', true)
                    ->where($active)
                    ->count();

                $count_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->where('residential_block.unit_no', '<=', 10)
                    ->count();

                $count_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->count();

                $total_less_10_units = $count_residential_less10 + $count_commercial_less10;

                $total_rating = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->count();

                $total_owner = DB::table('buyer')
                    ->join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
                    ->count();
            } else {
                $total_developer = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('management.is_developer', 1)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();
                $total_liquidator = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 1)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_jmb = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where('management.is_jmb', 1)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_mc = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_mc', 1)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_agent = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_agent', 1)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_others = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 1)
                    ->where('management.no_management', 0)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where('management.no_management', 1)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management_1 = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('management.is_developer', 0)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $fiveStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition5)
                    ->count();

                $fourStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition4)
                    ->count();

                $threeStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition3)
                    ->count();

                $twoStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition2)
                    ->count();

                $oneStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition1)
                    ->count();

                $total_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->count();

                $total_active_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', true)
                    ->where($active)
                    ->count();

                $total_inactive_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', '!=', true)
                    ->where($active)
                    ->count();

                $count_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->where('residential_block.unit_no', '<=', 10)
                    ->count();

                $count_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->count();

                $total_less_10_units = $count_residential_less10 + $count_commercial_less10;

                $total_rating = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($active)
                    ->count();

                $total_owner = DB::table('buyer')
                    ->join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
                    ->count();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $total_developer = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('management.is_developer', 1)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();
                $total_liquidator = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 1)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_jmb = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where('management.is_jmb', 1)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_mc = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_mc', 1)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_agent = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_agent', 1)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_others = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 1)
                    ->where('management.no_management', 0)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where('management.no_management', 1)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management_1 = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('management.is_developer', 0)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $fiveStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($condition5)
                    ->count();

                $fourStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($condition4)
                    ->count();

                $threeStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($condition3)
                    ->count();

                $twoStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($condition2)
                    ->count();

                $oneStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($condition1)
                    ->count();

                $total_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($active)
                    ->count();

                $total_active_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.is_active', true)
                    ->where($active)
                    ->count();

                $total_inactive_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.is_active', '!=', true)
                    ->where($active)
                    ->count();

                $count_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($active)
                    ->where('residential_block.unit_no', '<=', 10)
                    ->count();

                $count_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($active)
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->count();

                $total_less_10_units = $count_residential_less10 + $count_commercial_less10;

                $total_rating = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where($active)
                    ->count();

                $total_owner = DB::table('buyer')
                    ->join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('tenant.is_deleted', 0)
                    ->where($active)
                    ->count();
            } else {
                $total_developer = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('management.is_developer', 1)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();
                $total_liquidator = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 1)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_jmb = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where('management.is_jmb', 1)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_mc = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_mc', 1)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_agent = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_agent', 1)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_others = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 1)
                    ->where('management.no_management', 0)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where('management.no_management', 1)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $total_no_management_1 = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('management.is_developer', 0)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($active)
                    ->count();

                $fiveStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition5)
                    ->count();

                $fourStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition4)
                    ->count();

                $threeStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition3)
                    ->count();

                $twoStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition2)
                    ->count();

                $oneStar = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition1)
                    ->count();

                $total_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($active)
                    ->count();

                $total_active_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_active', true)
                    ->where($active)
                    ->count();

                $total_inactive_strata = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_active', '!=', true)
                    ->where($active)
                    ->count();

                $count_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($active)
                    ->where('residential_block.unit_no', '<=', 10)
                    ->count();

                $count_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($active)
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->count();

                $total_less_10_units = $count_residential_less10 + $count_commercial_less10;

                $total_rating = DB::table('scoring_quality_index')
                    ->join('files', 'scoring_quality_index.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($active)
                    ->count();

                $total_owner = DB::table('buyer')
                    ->join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('buyer.is_deleted', 0)
                    ->where($active)
                    ->count();

                $total_tenant = DB::table('tenant')
                    ->join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('tenant.is_deleted', 0)
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
        $never = [
            'categories' => [],
            'data' => [],
        ];
        $items_never = self::neverHasAGM()
            ->selectRaw('count(files.id) as total, company.short_name')
            ->groupBy(['company.short_name'])
            ->get();

        foreach ($items_never as $item) {
            $council = Company::where('short_name', $item->short_name)->first();
            $total_files = self::where('company_id', $council->id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $percentage = 0;
            if ($total_files > 0 && $item->total > 0) {
                $percentage = ($item->total / $total_files) * 100;
            }

            array_push($never['categories'], [$item->short_name]);
            array_push($never['data'], [round($percentage, 2)]);
        }

        $result = array(
            'rating' => $rating,
            'management' => $management,
            'never' => $never,
            'total_agent' => $total_agent,
            'total_developer' => $total_developer,
            'total_liquidator' => $total_liquidator,
            'total_strata' => $total_strata,
            'total_active_strata' => $total_active_strata,
            'total_inactive_strata' => $total_inactive_strata,
            'total_less_10_units' => $total_less_10_units,
            'total_no_management' => $total_no_management + $total_no_management_1,
            'total_jmb' => $total_jmb,
            'total_mc' => $total_mc,
            'total_owner' => $total_owner,
            'total_tenant' => $total_tenant,
            'total_rating' => $total_rating
        );

        return $result;
    }

    public static function getStrataProfileAnalytic($request = [])
    {
        $query = Files::with(['financeLatest', 'company'])
            ->file();

        if (!empty($request['company_id'])) {
            $company = Company::where('short_name', $request['company_id'])->first();
            $query = $query->where('files.company_id', $company->id);
        }
        $pie_data = [
            'Biru' => 0,
            'Kuning' => 0,
            'Merah' => 0,
            'Kelabu' => 0
        ];
        $items = $query->chunk(500, function ($files) use (&$pie_data) {
            foreach ($files as $file) {
                $finance = $file->financeLatest;
                if ($finance) {
                    $finance_income_semasa = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                    $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                    if ($finance_report_fee_semasa > 0) {
                        $percentage = round(($finance_income_semasa / $finance_report_fee_semasa) * 100);

                        if ($percentage >= 80) {
                            $pie_data['Biru'] += 1;
                        } else if ($percentage < 79 && $percentage >= 50) {
                            $pie_data['Kuning'] += 1;
                        } else {
                            $pie_data['Merah'] += 1;
                        }
                    } else {
                        $pie_data['Merah'] += 1;
                    }
                } else {
                    $pie_data['Kelabu'] += 1;
                }
            }
        });

        $data['pie_data'] = [
            ['name' => 'Biru', 'slug' => 'biru', 'y' => $pie_data['Biru']],
            ['name' => 'Kuning', 'slug' => 'kuning', 'y' => $pie_data['Kuning']],
            ['name' => 'Merah', 'slug' => 'merah', 'y' => $pie_data['Merah']],
            ['name' => 'Kelabu', 'slug' => 'gray', 'y' => $pie_data['Kelabu']],
        ];
        return $data;
    }

    public static function getFileName()
    {
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

    public static function getRatingByCategory()
    {
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

    public static function getManagementSummaryCOB()
    {
        $developer = 0;
        $liquidator = 0;
        $jmb = 0;
        $mc = 0;
        $agent = 0;
        $others = 0;
        $no_management = 0;
        $bankruptcy = 0;
        $residential = 0;
        $commercial = 0;
        $count_less3 = 0;
        $count_more3 = 0;
        $count_less10 = 0;
        $count_more10 = 0;
        $count_all = 0;
        $sum_less3 = 0;
        $sum_more3 = 0;
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
        $condition_mps = function ($query) {
            if (Auth::user()->getCOB->short_name == 'MPS') {
                $query->where('files.company_id', 8);
            }
        };

        if ($company) {
            foreach ($company as $cob) {
                $total_developer = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where('management.is_developer', 1)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_jmb = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where('management.is_jmb', 1)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_mc = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_mc', 1)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_agent = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where('management.is_agent', 1)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_liquidator = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 1)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_others = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where('management.is_others', 1)
                    ->where('management.no_management', 0)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_no_management = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where('management.no_management', 1)
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_no_management_1 = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where('management.is_developer', 0)
                    ->where('management.is_jmb', 0)
                    ->where('management.is_mc', 0)
                    ->where('management.is_agent', 0)
                    ->where('management.is_others', 0)
                    ->where('management.no_management', 0)
                    ->where('management.liquidator', 0)
                    ->where('management.bankruptcy', 0)
                    ->where($condition_mps)
                    ->count();

                $total_bankruptcy = DB::table('management')
                    ->join('files', 'management.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->where('files.is_active', '!=', 2)
                    ->where(function ($query) {
                        $query->where('management.is_developer', 0)
                            ->orWhere('management.is_developer', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_mc', 0)
                            ->orWhere('management.is_mc', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_jmb', 0)
                            ->orWhere('management.is_jmb', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_agent', 0)
                            ->orWhere('management.is_agent', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.is_others', 0)
                            ->orWhere('management.is_others', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.no_management', 0)
                            ->orWhere('management.no_management', 1);
                    })
                    ->where(function ($query) {
                        $query->where('management.liquidator', 0)
                            ->orWhere('management.liquidator', 1);
                    })
                    ->where('management.bankruptcy', 1)
                    ->where($condition_mps)
                    ->count();

                $count_residential = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->count('residential_block.unit_no');

                $count_residential_less3 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '<=', 3)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->count('residential_block.unit_no');

                $count_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '<=', 10)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->count('residential_block.unit_no');

                $count_residential_more3 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '>', 3)
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

                $count_commercial_less3 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '<=', 3)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->count('commercial_block.unit_no');

                $count_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->count('commercial_block.unit_no');

                $count_commercial_more3 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '>', 3)
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

                $sum_residential_less3 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '<=', 3)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->sum('residential_block.unit_no');

                $sum_residential_less10 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '<=', 10)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->sum('residential_block.unit_no');

                $sum_residential_more3 = DB::table('residential_block')
                    ->join('files', 'residential_block.file_id', '=', 'files.id')
                    ->where('residential_block.unit_no', '>', 3)
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

                $sum_commercial_less3 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '<=', 3)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->sum('commercial_block.unit_no');

                $sum_commercial_less10 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '<=', 10)
                    ->where('files.company_id', $cob->id)
                    ->where('files.is_deleted', 0)
                    ->sum('commercial_block.unit_no');

                $sum_commercial_more3 = DB::table('commercial_block')
                    ->join('files', 'commercial_block.file_id', '=', 'files.id')
                    ->where('commercial_block.unit_no', '>', 3)
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
                $no_management = $no_management + $total_no_management + $total_no_management_1;
                $bankruptcy = $bankruptcy + $total_bankruptcy;
                $residential = $residential + $sum_residential;
                $commercial = $commercial + $sum_commercial;
                $count_less3 = $count_less3 + ($count_residential_less3 + $count_commercial_less3);
                $count_more3 = $count_more3 + ($count_residential_more3 + $count_commercial_more3);
                $count_less10 = $count_less10 + ($count_residential_less10 + $count_commercial_less10);
                $count_more10 = $count_more10 + ($count_residential_more10 + $count_commercial_more10);
                $count_all = $count_all + ($count_residential + $count_commercial);
                $sum_less3 = $sum_less3 + ($sum_residential_less3 + $sum_commercial_less3);
                $sum_less10 = $sum_less10 + ($sum_residential_less10 + $sum_commercial_less10);
                $sum_more3 = $sum_more3 + ($sum_residential_more3 + $sum_commercial_more3);
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
            'no_management' => $no_management,
            'bankruptcy' => $bankruptcy,
            'residential' => $residential,
            'commercial' => $commercial,
            'count_less3' => $count_less3,
            'count_more3' => $count_more3,
            'count_less10' => $count_less10,
            'count_more10' => $count_more10,
            'count_all' => $count_all,
            'sum_less3' => $sum_less3,
            'sum_more3' => $sum_more3,
            'sum_less10' => $sum_less10,
            'sum_more10' => $sum_more10,
            'sum_all' => $sum_all,
            'total_all' => $total_all
        );


        return $result;
    }

    public static function getLandTitleReportByCOB($cob_id = NULL, $land_title_id = NULL)
    {
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

    public function draft()
    {
        return $this->hasOne('FileDrafts', 'file_id');
    }

    public function hasDraft()
    {
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

    public static function parkList()
    {
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

    public static function categoryList()
    {
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

    public static function fileList()
    {
        $files = '';

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::join('strata', 'files.id', '=', 'strata.file_id')
                    ->select('files.id as file_id', DB::raw("CONCAT(files.file_no,' - ', strata.name) as strata_name"))
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_deleted', 0)
                    ->orderBy('files.status', 'asc')
                    ->lists('strata_name', 'file_id');
            } else {
                $files = Files::join('strata', 'files.id', '=', 'strata.file_id')
                    ->select('files.id as file_id', DB::raw("CONCAT(files.file_no,' - ', strata.name) as strata_name"))
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_deleted', 0)
                    ->orderBy('files.status', 'asc')
                    ->lists('strata_name', 'file_id');
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::join('strata', 'files.id', '=', 'strata.file_id')
                    ->select('files.id as file_id', DB::raw("CONCAT(files.file_no,' - ', strata.name) as strata_name"))
                    ->where('files.is_deleted', 0)
                    ->orderBy('files.status', 'asc')
                    ->lists('strata_name', 'file_id');
            } else {
                $files = Files::join('strata', 'files.id', '=', 'strata.file_id')
                    ->select('files.id as file_id', DB::raw("CONCAT(files.file_no,' - ', strata.name) as strata_name"))
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_deleted', 0)
                    ->orderBy('files.status', 'asc')
                    ->lists('strata_name', 'file_id');
            }
        }

        return $files;
    }

    public function lastAGMDate()
    {
        $model = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
            ->select('meeting_document.agm_date')
            ->whereNotNull('meeting_document.agm_date')
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.is_deleted', 0)
            ->where('files.id', $this->id)
            ->orderBy('meeting_document.agm_date', 'desc')
            ->first();

        if ($model) {
            return $model->agm_date;
        }

        return '';
    }

    public function epks()
    {
        return $this->hasOne('Epks', 'file_id');
    }

    public function approvedEpks()
    {
        return $this->hasOne('Epks', 'file_id')->where('epks.status', Epks::APPROVED);
    }

    public function financeAgeing()
    {
        $result = [];
        $data = [];
        $graph_month = [];
        $graph_percentage = [];

        $start = Carbon::today()->subMonth()->subYear();

        for ($i = 0; $i < 12; $i++) {
            $percentage = 0;
            $total_income = 0;
            $total_expense = 0;
            $nett_income = 0;

            $date = $start->addMonth();

            $month_finance = Finance::with(['financeIncome', 'financeSummary', 'financeReportMF', 'financeReportSF', 'financeReportMFExtra', 'financeReportSFExtra', 'financeIncomeMF', 'financeIncomeSF'])
                ->where('finance_file.file_id', $this->id)
                ->where('finance_file.year', $date->format('Y'))
                ->where('finance_file.month', $date->format('m'))
                ->where('finance_file.is_active', 1)
                ->where('finance_file.is_deleted', 0)
                ->first();

            if (!empty($month_finance)) {
                $mf_fee_semasa = $month_finance->financeReportMF->sum('fee_semasa');
                $sf_fee_semasa = $month_finance->financeReportSF->sum('fee_semasa');
                $fee_semasa = $mf_fee_semasa + $sf_fee_semasa;

                $mf_fee_semasa_extra = $month_finance->financeReportMFExtra->sum('fee_semasa');
                $sf_fee_semasa_extra = $month_finance->financeReportSFExtra->sum('fee_semasa');
                $fee_semasa_extra = $mf_fee_semasa_extra + $sf_fee_semasa_extra;
                $total_sepatut_dikutip = $fee_semasa + $fee_semasa_extra;

                $mf_income = $month_finance->financeIncomeMF->sum('semasa');
                $sf_income = $month_finance->financeIncomeSF->sum('semasa');
                $total_berjaya_dikutip = $mf_income + $sf_income;

                if ($total_berjaya_dikutip > 0 && $total_sepatut_dikutip > 0) {
                    $percentage = ($total_berjaya_dikutip / $total_sepatut_dikutip) * 100;
                }

                $total_income_tuggakan = $month_finance->financeIncome->sum('tunggakan');
                $total_income_semasa = $month_finance->financeIncome->sum('semasa');
                $total_income_hadapan = $month_finance->financeIncome->sum('hadapan');
                $total_income = $total_income_tuggakan + $total_income_semasa + $total_income_hadapan;

                $total_expense = $month_finance->financeSummary->sum('amount');

                if ($total_income > 0 && $total_expense > 0) {
                    $nett_income = $total_income - $total_expense;
                }
            }

            array_push($graph_month, $date->format('F') . '<br />' . $date->format('Y'));
            array_push($graph_percentage, round($percentage));
            array_push($data, [
                'year' => $date->format('Y'),
                'month' => $date->format('m'),
                'month_name' => $date->format('F'),
                'percentage' => round($percentage),
                'total_income' => number_format($total_income, 2),
                'total_expense' => number_format($total_expense, 2),
                'nett_income' => number_format($nett_income, 2),
            ]);
        }

        $result = [
            'data' => $data,
            'graph' => [
                'months' => $graph_month,
                'percentages' => $graph_percentage,
            ],
        ];

        return $result;
    }
}
