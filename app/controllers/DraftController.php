<?php

use Helper\Helper;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Facades\Datatables;

class DraftController extends BaseController {

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

    public function fileList() {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_hidden', false)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->get();
            }

            $viewData = array(
                'title' => trans('app.menus.cob.file_draft_list'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'cob' => $cob,
                'image' => ""
            );

            return View::make('draft.file_list', $viewData);
        }

        return $this->errorPage();
    }

    public function getFileList() {
        $file = Files::file()
                    ->join('file_drafts', 'files.id', '=', 'file_drafts.file_id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'strata.id as strata_id', 'file_drafts.created_at as draft_created'])
                    ->where('files.is_active', '!=', 2)
                    ->where('file_drafts.is_deleted', 0);

        if (empty(Session::get('admin_cob'))) {  
            $file = $file->where('company.is_hidden', false);
        }

        return Datatables::of($file)
                        ->addColumn('cob', function ($model) {
                            return ($model->company_id ? $model->company->short_name : '-');
                        })
                        ->editColumn('file_no', function ($model) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('DraftController@houseScheme', Helper::encode($model->id)) . "'>" . $model->file_no . "</a>";
                        })
                        ->editColumn('draft_created', function ($model) {
                            return date('d/m/Y', strtotime($model->draft_created));
                        })
                        ->addColumn('strata', function ($model) {
                            return ($model->strata_id ? $model->strata->name : '-');
                        })
                        ->addColumn('year', function ($model) {
                            return ($model->strata->year != '0' ? $model->strata->year : '');
                        })
                        ->addColumn('active', function ($model) {
                            if ($model->is_active == 1) {
                                $is_active = trans('app.forms.yes');
                            } else {
                                $is_active = trans('app.forms.no');
                            }

                            return $is_active;
                        })
                        ->addColumn('action', function ($model) {
                            $button = '';
                            $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFile(\'' . Helper::encode($model->id) . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

                            return $button;
                        })
                        ->make(true);
    }

    public function houseScheme($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail(Helper::decode($id));

            if (!$files->hasDraft()) {
                return Redirect::to('/draft/fileList');
            } else if (!$files->houseScheme->draft) {
                return Redirect::to('/draft/strata/' . Helper::encode($files->id));
            }

            $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            $developer = Developer::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $liquidator = Liquidator::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $users = User::where('company_id', $files->company_id)->where('is_active', 1)->where('status', 1)->where('is_deleted', 0)->orderBy('full_name', 'asc')->get();

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'developer' => $developer,
                'liquidator' => $liquidator,
                'house_scheme' => $house_scheme,
                'city' => $city,
                'country' => $country,
                'state' => $state,
                'file' => $files,
                'users' => $users,
                'image' => (!empty($files->other->image_url) ? $files->other->image_url : '')
            );

            return View::make('draft.house_scheme', $viewData);
        }

        return $this->errorPage();
    }

    public function submitHouseScheme() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $draft_house = HouseSchemeDraft::where('file_id', $files->id)->where('is_deleted', 0)->first();

            if ($draft_house && !empty($draft_house->reference_id)) {
                $house_scheme = HouseScheme::findOrFail($draft_house->reference_id);
                /** Arrange audit fields changes */
                $developer_name_field = $draft_house->name == $house_scheme->name? "": "developer name";
                $developer_field = $draft_house->developer == $house_scheme->developer? "": "developer id";
                $liquidator_field = $draft_house->liquidator == $house_scheme->liquidator? "": "liquidator id";
                $developer_address1_field = $draft_house->address1 == $house_scheme->address1? "": "developer address1";
                $developer_address2_field = $draft_house->address2 == $house_scheme->address2? "": "developer address2";
                $developer_address3_field = $draft_house->address3 == $house_scheme->address3? "": "developer address3";
                $developer_address4_field = $draft_house->address4 == $house_scheme->address4? "": "developer address4";
                $developer_city_field = $draft_house->city == $house_scheme->city? "": "developer city";
                $developer_poscode_field = $draft_house->poscode == $house_scheme->poscode? "": "developer poscode";
                $developer_state_field = $draft_house->state == $house_scheme->state? "": "developer state";
                $developer_country_field = $draft_house->country == $house_scheme->country? "": "developer country";
                $developer_phone_no_field = $draft_house->phone_no == $house_scheme->phone_no? "": "developer phone no";
                $developer_fax_no_field = $draft_house->fax_no == $house_scheme->fax_no? "": "developer fax no";
                $developer_remarks_field = $draft_house->remarks == $house_scheme->remarks? "": "developer remarks";
                $developer_is_active_field = $draft_house->is_active == $house_scheme->is_active? "": "developer status";
                $liquidator_name_field = $draft_house->liquidator_name == $house_scheme->liquidator_name? "": "liquidator name";
                $liquidator_address1_field = $draft_house->liquidator_address1 == $house_scheme->liquidator_address1? "": "liquidator address1";
                $liquidator_address2_field = $draft_house->liquidator_address2 == $house_scheme->liquidator_address2? "": "liquidator address2";
                $liquidator_address3_field = $draft_house->liquidator_address3 == $house_scheme->liquidator_address3? "": "liquidator address3";
                $liquidator_address4_field = $draft_house->liquidator_address4 == $house_scheme->liquidator_address4? "": "liquidator address4";
                $liquidator_city_field = $draft_house->liquidator_city == $house_scheme->liquidator_city? "": "liquidator city";
                $liquidator_poscode_field = $draft_house->liquidator_poscode == $house_scheme->liquidator_poscode? "": "liquidator poscode";
                $liquidator_state_field = $draft_house->liquidator_state == $house_scheme->liquidator_state? "": "liquidator state";
                $liquidator_country_field = $draft_house->liquidator_country == $house_scheme->liquidator_country? "": "liquidator country";
                $liquidator_phone_no_field = $draft_house->liquidator_phone_no == $house_scheme->liquidator_phone_no? "": "liquidator phone no";
                $liquidator_fax_no_field = $draft_house->liquidator_fax_no == $house_scheme->liquidator_fax_no? "": "liquidator fax no";
                $liquidator_remarks_field = $draft_house->liquidator_remarks == $house_scheme->liquidator_remarks? "": "liquidator remarks";
                $liquidator_is_active_field = $draft_house->liquidator_is_active == $house_scheme->liquidator_is_active? "": "liquidator status";
    
                $audit_fields_changed = "";
                if(!empty($developer_name_field) || !empty($developer_field) || !empty($liquidator_field) || !empty($developer_address1_field)
                || !empty($developer_address2_field) || !empty($developer_address3_field) || !empty($developer_address4_field) || !empty($developer_city_field)
                || !empty($developer_poscode_field) || !empty($developer_state_field) || !empty($developer_country_field) || !empty($developer_phone_no_field)
                || !empty($developer_fax_no_field) || !empty($developer_remarks_field) || !empty($developer_is_active_field) || !empty($liquidator_name_field)
                || !empty($liquidator_address1_field) || !empty($liquidator_address2_field) || !empty($liquidator_address3_field) || !empty($liquidator_address4_field)
                || !empty($liquidator_city_field) || !empty($liquidator_poscode_field) || !empty($liquidator_state_field) || !empty($liquidator_country_field)
                || !empty($liquidator_phone_no_field) || !empty($liquidator_fax_no_field) || !empty($liquidator_remarks_field) || !empty($liquidator_is_active_field)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= !empty($developer_name_field)? "<li>$developer_name_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_field)? "<li>$developer_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_field)? "<li>$liquidator_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_address1_field)? "<li>$developer_address1_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_address2_field)? "<li>$developer_address2_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_address3_field)? "<li>$developer_address3_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_address4_field)? "<li>$developer_address4_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_city_field)? "<li>$developer_city_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_poscode_field)? "<li>$developer_poscode_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_state_field)? "<li>$developer_state_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_country_field)? "<li>$developer_country_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_phone_no_field)? "<li>$developer_phone_no_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_fax_no_field)? "<li>$developer_fax_no_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_remarks_field)? "<li>$developer_remarks_field</li>" : "";
                    $audit_fields_changed .= !empty($developer_is_active_field)? "<li>$developer_is_active_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_name_field)? "<li>$liquidator_name_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_address1_field)? "<li>$liquidator_address1_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_address2_field)? "<li>$liquidator_address2_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_address3_field)? "<li>$liquidator_address3_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_address4_field)? "<li>$liquidator_address4_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_city_field)? "<li>$liquidator_city_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_poscode_field)? "<li>$liquidator_poscode_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_state_field)? "<li>$liquidator_state_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_country_field)? "<li>$liquidator_country_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_phone_no_field)? "<li>$liquidator_phone_no_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_fax_no_field)? "<li>$liquidator_fax_no_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_remarks_field)? "<li>$liquidator_remarks_field</li>" : "";
                    $audit_fields_changed .= !empty($liquidator_is_active_field)? "<li>$liquidator_is_active_field</li>" : "";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                $house_scheme->name = $draft_house->name;
                $house_scheme->developer = $draft_house->developer;
                $house_scheme->liquidator = $draft_house->liquidator;
                $house_scheme->address1 = $draft_house->address1;
                $house_scheme->address2 = $draft_house->address2;
                $house_scheme->address3 = $draft_house->address3;
                $house_scheme->address4 = $draft_house->address4;
                $house_scheme->city = $draft_house->city;
                $house_scheme->poscode = $draft_house->poscode;
                $house_scheme->state = $draft_house->state;
                $house_scheme->country = $draft_house->country;
                $house_scheme->phone_no = $draft_house->phone_no;
                $house_scheme->fax_no = $draft_house->fax_no;
                $house_scheme->remarks = $draft_house->remarks;
                $house_scheme->is_active = $draft_house->is_active;
                /** Liquidator Info */
                $house_scheme->liquidator_name = $draft_house->liquidator_name;
                $house_scheme->liquidator_address1 = $draft_house->liquidator_address1;
                $house_scheme->liquidator_address2 = $draft_house->liquidator_address2;
                $house_scheme->liquidator_address3 = $draft_house->liquidator_address3;
                $house_scheme->liquidator_address4 = $draft_house->liquidator_address4;
                $house_scheme->liquidator_city = $draft_house->liquidator_city;
                $house_scheme->liquidator_poscode = $draft_house->liquidator_poscode;
                $house_scheme->liquidator_state = $draft_house->liquidator_state;
                $house_scheme->liquidator_country = $draft_house->liquidator_country;
                $house_scheme->liquidator_phone_no = $draft_house->liquidator_phone_no;
                $house_scheme->liquidator_fax_no = $draft_house->liquidator_fax_no;
                $house_scheme->liquidator_remarks = $draft_house->liquidator_remarks;
                $house_scheme->is_liquidator = $draft_house->is_liquidator;
                $house_scheme->liquidator_is_active = $draft_house->liquidator_is_active;
                $house_scheme->save();

                // clear draft
                $draft_house->delete();

                // clear main draft
                $this->clearDraft($files);

                # Audit Trail
                if(!empty($audit_fields_changed)) {
                    $remarks = 'House Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }

                return "true";
            }
        }

        return "false";
    }

    public function strata($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail(Helper::decode($id));

            if (!$files->strata->draft) {
                return Redirect::to('/draft/management/' . Helper::encode($files->id));
            }

            $strata = Strata::where('file_id', $files->id)->first();
            $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

            if ($strata->dun != 0) {
                $dun = Dun::where('parliament', $strata->parliament)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            } else {
                $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            }
            if ($strata->park != 0) {
                $park = Park::where('dun', $strata->dun)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            } else {
                $park = Park::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            }
            $area = Area::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $unit = UnitMeasure::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $land_title = LandTitle::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $perimeter = Perimeter::where('is_active', 1)->where('is_deleted', 0)->orderBy('description_en', 'asc')->get();
            $unitoption = UnitOption::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'strata' => $strata,
                'city' => $city,
                'country' => $country,
                'state' => $state,
                'parliament' => $parliament,
                'dun' => $dun,
                'park' => $park,
                'area' => $area,
                'unit' => $unit,
                'land_title' => $land_title,
                'category' => $category,
                'perimeter' => $perimeter,
                'file' => $files,
                'unitoption' => $unitoption,
                'designation' => $designation,
                'image' => (!empty($files->other->image_url) ? $files->other->image_url : '')
            );
            return View::make('draft.strata', $viewData);
        }

        return $this->errorPage();
    }

    public function submitStrata() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $draft_strata = StrataDraft::where('file_id', $files->id)->first();
            $draft_residential = ResidentialDraft::where('file_id', $files->id)->first();
            $draft_commercial = CommercialDraft::where('file_id', $files->id)->first();
            $draft_residential_extra = ResidentialExtraDraft::where('file_id', $files->id)->get();
            $draft_commercial_extra = CommercialExtraDraft::where('file_id', $files->id)->get();
            $draft_facility = FacilityDraft::where('file_id', $files->id)->first();

            if ($draft_strata && !empty($draft_strata->reference_id)) {
                // strata
                $strata = Strata::findOrFail($draft_strata->reference_id);
                $residential = Residential::where('file_id', $files->id)->first();
                $commercial = Commercial::where('file_id', $files->id)->first();
                $residentialExtra = ResidentialExtra::where('file_id', $files->id)->get();
                $commercialExtra = CommercialExtra::where('file_id', $files->id)->get();
                $facility = Facility::where('file_id', $files->id)->first();
                
                /** Arrange audit fields changes */
                $strata_title_field = $draft_strata->title == $strata->title? "": "strata title";
                $strata_name_field = $draft_strata->name == $strata->name? "": "strata name";
                $strata_parliament_field = $draft_strata->parliament == $strata->parliament? "": "strata parliament";
                $strata_dun_field = $draft_strata->dun == $strata->dun? "": "strata dun";
                $strata_park_field = $draft_strata->park == $strata->park? "": "strata park";
                $strata_address1_field = $draft_strata->address1 == $strata->address1? "": "strata address1";
                $strata_address2_field = $draft_strata->address2 == $strata->address2? "": "strata address2";
                $strata_address3_field = $draft_strata->address3 == $strata->address3? "": "strata address3";
                $strata_address4_field = $draft_strata->address4 == $strata->address4? "": "strata address4";
                $strata_poscode_field = $draft_strata->poscode == $strata->poscode? "": "strata poscode";
                $strata_city_field = $draft_strata->city == $strata->city? "": "strata city";
                $strata_state_field = $draft_strata->state == $strata->state? "": "strata state";
                $strata_country_field = $draft_strata->country == $strata->country? "": "strata country";
                $strata_block_no_field = $draft_strata->block_no == $strata->block_no? "": "strata block no";
                $strata_total_floor_field = $draft_strata->total_floor == $strata->total_floor? "": "strata total floor";
                $strata_year_field = $draft_strata->year == $strata->year? "": "strata year";
                $strata_town_field = $draft_strata->town == $strata->town? "": "strata town";
                $strata_area_field = $draft_strata->area == $strata->area? "": "strata area";
                $strata_land_area_field = $draft_strata->land_area == $strata->land_area? "": "strata land area";
                $strata_total_share_unit_field = $draft_strata->total_share_unit == $strata->total_share_unit? "": "strata total share unit";
                $strata_land_area_unit_field = $draft_strata->land_area_unit == $strata->land_area_unit? "": "strata land area unit";
                $strata_lot_no_field = $draft_strata->lot_no == $strata->lot_no? "": "strata lot no";
                $strata_ownership_no_field = $draft_strata->ownership_no == $strata->ownership_no? "": "strata ownership no";
                $strata_date_field = $draft_strata->date == $strata->date? "": "strata date";
                $strata_land_title_field = $draft_strata->land_title == $strata->land_title? "": "strata land title";
                $strata_category_field = $draft_strata->category == $strata->category? "": "strata category";
                $strata_perimeter_field = $draft_strata->perimeter == $strata->perimeter? "": "strata perimeter";
                $strata_ccc_no_field = $draft_strata->ccc_no == $strata->ccc_no? "": "strata ccc_no";
                $strata_ccc_date_field = $draft_strata->ccc_date == $strata->ccc_date? "": "strata ccc_date";
                $strata_file_url_field = $draft_strata->file_url == $strata->file_url? "": "strata file";
                $strata_is_residential_field = $draft_strata->is_residential == $strata->is_residential? "": "strata is residential";
                $strata_is_commercial_field = $draft_strata->is_commercial == $strata->is_commercial? "": "strata is commercial";
                $residential_text = '';
                $residential_unit_no_field = '';
                $residential_maintenance_fee_field = '';
                $residential_maintenance_fee_option_field = '';
                $residential_sinking_fund_field = '';
                $residential_sinking_fund_option_field = '';
                $commercial_text = '';
                $commercial_unit_no_field = '';
                $commercial_maintenance_fee_field = '';
                $commercial_maintenance_fee_option_field = '';
                $commercial_sinking_fund_field = '';
                $commercial_sinking_fund_option_field = '';
                if($strata->is_residential) {
                    if(empty($draft_residential)) {
                        $residential_text = 'remove residential data';
                    } else {
                        $residential_unit_no_field = $draft_residential->unit_no == $residential->unit_no? "": "strata residential unit no";
                        $residential_maintenance_fee_field = $draft_residential->maintenance_fee == $residential->maintenance_fee? "": "strata residential maintenance fee";
                        $residential_maintenance_fee_option_field = $draft_residential->maintenance_fee_option == $residential->maintenance_fee_option? "": "strata residential maintenance fee option";
                        $residential_sinking_fund_field = $draft_residential->sinking_fund == $residential->sinking_fund? "": "strata residential sinking fund";
                        $residential_sinking_fund_option_field = $draft_residential->sinking_fund_option == $residential->sinking_fund_option? "": "strata residential sinking fund option";
                    }
                } else {
                    $residential_text = 'new residential data';
                }
                if($strata->is_commercial) {
                    if(empty($draft_commercial)) {
                        $commercial_text = 'remove commercial data';
                    } else {
                        $commercial_unit_no_field = $draft_commercial->unit_no == $commercial->unit_no? "": "strata commercial unit no";
                        $commercial_maintenance_fee_field = $draft_commercial->maintenance_fee == $commercial->maintenance_fee? "": "strata commercial maintenance fee";
                        $commercial_maintenance_fee_option_field = $draft_commercial->maintenance_fee_option == $commercial->maintenance_fee_option? "": "strata commercial maintenance fee option";
                        $commercial_sinking_fund_field = $draft_commercial->sinking_fund == $commercial->sinking_fund? "": "strata commercial sinking fund";
                        $commercial_sinking_fund_option_field = $draft_commercial->sinking_fund_option == $commercial->sinking_fund_option? "": "strata commercial sinking fund option";
                    }
                } else {
                    $commercial_text = 'new commercial data';
                }
                $facility_management_office_field = $draft_facility->management_office == $facility->management_office? "": "strata facility management office";
                $facility_management_office_unit_field = $draft_facility->management_office_unit == $facility->management_office_unit? "": "strata facility management office unit";
                $facility_swimming_pool_field = $draft_facility->swimming_pool == $facility->swimming_pool? "": "strata facility swimming pool";
                $facility_swimming_pool_unit_field = $draft_facility->swimming_pool_unit == $facility->swimming_pool_unit? "": "strata facility swimming pool unit";
                $facility_surau_field = $draft_facility->surau == $facility->surau? "": "strata facility surau";
                $facility_surau_unit_field = $draft_facility->surau_unit == $facility->surau_unit? "": "strata facility surau unit";
                $facility_multipurpose_hall_field = $draft_facility->multipurpose_hall == $facility->multipurpose_hall? "": "strata facility multipurpose hall";
                $facility_multipurpose_hall_unit_field = $draft_facility->multipurpose_hall_unit == $facility->multipurpose_hall_unit? "": "strata facility multipurpose hall unit";
                $facility_gym_field = $draft_facility->gym == $facility->gym? "": "strata facility gym";
                $facility_gym_unit_field = $draft_facility->gym_unit == $facility->gym_unit? "": "strata facility gym unit";
                $facility_playground_field = $draft_facility->playground == $facility->playground? "": "strata facility playground";
                $facility_playground_unit_field = $draft_facility->playground_unit == $facility->playground_unit? "": "strata facility playground unit";
                $facility_guardhouse_field = $draft_facility->guardhouse == $facility->guardhouse? "": "strata facility guardhouse";
                $facility_guardhouse_unit_field = $draft_facility->guardhouse_unit == $facility->guardhouse_unit? "": "strata facility guardhouse unit";
                $facility_kindergarten_field = $draft_facility->kindergarten == $facility->kindergarten? "": "strata facility kindergarten";
                $facility_kindergarten_unit_field = $draft_facility->kindergarten_unit == $facility->kindergarten_unit? "": "strata facility kindergarten unit";
                $facility_open_space_field = $draft_facility->open_space == $facility->open_space? "": "strata facility open space";
                $facility_open_space_unit_field = $draft_facility->open_space_unit == $facility->open_space_unit? "": "strata facility open space unit";
                $facility_lift_field = $draft_facility->lift == $facility->lift? "": "strata facility lift";
                $facility_lift_unit_field = $draft_facility->lift_unit == $facility->lift_unit? "": "strata facility lift unit";
                $facility_rubbish_room_field = $draft_facility->rubbish_room == $facility->rubbish_room? "": "strata facility rubbish room";
                $facility_rubbish_room_unit_field = $draft_facility->rubbish_room_unit == $facility->rubbish_room_unit? "": "strata facility rubbish room unit";
                $facility_gated_field = $draft_facility->gated == $facility->gated? "": "strata facility gated";
                $facility_gated_unit_field = $draft_facility->gated_unit == $facility->gated_unit? "": "strata facility gated unit";
                $facility_others_field = $draft_facility->others == $facility->others? "": "strata facility others";

    
                $audit_fields_changed = "";
                $audit_fields_changed .= "<br><ul>";
                /** Strata */
                if(!empty($strata_title_field) || !empty($strata_name_field) || !empty($strata_parliament_field) || !empty($strata_dun_field)
                || !empty($strata_park_field) || !empty($strata_address1_field) || !empty($strata_address2_field) || !empty($strata_address3_field)
                || !empty($strata_address4_field) || !empty($strata_poscode_field) || !empty($strata_city_field) || !empty($strata_state_field)
                || !empty($strata_country_field) || !empty($strata_block_no_field) || !empty($strata_total_floor_field) || !empty($strata_year_field)
                || !empty($strata_town_field) || !empty($strata_area_field) || !empty($strata_land_area_field) || !empty($strata_total_share_unit_field)
                || !empty($strata_land_area_unit_field) || !empty($strata_lot_no_field) || !empty($strata_ownership_no_field) || !empty($strata_date_field)
                || !empty($strata_land_title_field) || !empty($strata_category_field) || !empty($strata_perimeter_field) || !empty($strata_ccc_no_field)
                || !empty($strata_ccc_date_field) || !empty($strata_file_url_field) || !empty($strata_is_residential_field) || !empty($strata_is_commercial_field)
                ) {
                    $audit_fields_changed .= "<li> Strata : (";
                    $new_line = '';
                    $new_line .= !empty($strata_title_field)? "$strata_title_field, " : "";
                    $new_line .= !empty($strata_name_field)? "$strata_name_field, " : "";
                    $new_line .= !empty($strata_parliament_field)? "$strata_parliament_field, " : "";
                    $new_line .= !empty($strata_dun_field)? "$strata_dun_field, " : "";
                    $new_line .= !empty($strata_park_field)? "$strata_park_field, " : "";
                    $new_line .= !empty($strata_address1_field)? "$strata_address1_field, " : "";
                    $new_line .= !empty($strata_address2_field)? "$strata_address2_field, " : "";
                    $new_line .= !empty($strata_address3_field)? "$strata_address3_field, " : "";
                    $new_line .= !empty($strata_address4_field)? "$strata_address4_field, " : "";
                    $new_line .= !empty($strata_poscode_field)? "$strata_poscode_field, " : "";
                    $new_line .= !empty($strata_city_field)? "$strata_city_field, " : "";
                    $new_line .= !empty($strata_state_field)? "$strata_state_field, " : "";
                    $new_line .= !empty($strata_country_field)? "$strata_country_field, " : "";
                    $new_line .= !empty($strata_block_no_field)? "$strata_block_no_field, " : "";
                    $new_line .= !empty($strata_total_floor_field)? "$strata_total_floor_field, " : "";
                    $new_line .= !empty($strata_year_field)? "$strata_year_field, " : "";
                    $new_line .= !empty($strata_town_field)? "$strata_town_field, " : "";
                    $new_line .= !empty($strata_area_field)? "$strata_area_field, " : "";
                    $new_line .= !empty($strata_land_area_field)? "$strata_land_area_field, " : "";
                    $new_line .= !empty($strata_total_share_unit_field)? "$strata_total_share_unit_field, " : "";
                    $new_line .= !empty($strata_land_area_unit_field)? "$strata_land_area_unit_field, " : "";
                    $new_line .= !empty($strata_lot_no_field)? "$strata_lot_no_field, " : "";
                    $new_line .= !empty($strata_ownership_no_field)? "$strata_ownership_no_field, " : "";
                    $new_line .= !empty($strata_date_field)? "$strata_date_field, " : "";
                    $new_line .= !empty($strata_land_title_field)? "$strata_land_title_field, " : "";
                    $new_line .= !empty($strata_category_field)? "$strata_category_field, " : "";
                    $new_line .= !empty($strata_perimeter_field)? "$strata_perimeter_field, " : "";
                    $new_line .= !empty($strata_ccc_no_field)? "$strata_ccc_no_field, " : "";
                    $new_line .= !empty($strata_ccc_date_field)? "$strata_ccc_date_field, " : "";
                    $new_line .= !empty($strata_file_url_field)? "$strata_file_url_field, " : "";
                    $new_line .= !empty($strata_is_residential_field)? "$strata_is_residential_field, " : "";
                    $new_line .= !empty($strata_is_commercial_field)? "$strata_is_commercial_field, " : "";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                }
                /** End Strata */
                /** Residential */
                if(empty($residential_text)) {
                    if(!empty($residential_unit_no_field) || !empty($residential_maintenance_fee_field) || !empty($residential_maintenance_fee_option_field) || !empty($residential_sinking_fund_field)
                    || !empty($residential_sinking_fund_option_field)) {
                        $audit_fields_changed .= "<li> Residential : (";
                        $new_line = '';
                        $new_line .= !empty($residential_unit_no_field)? "$residential_unit_no_field, " : "";
                        $new_line .= !empty($residential_maintenance_fee_field)? "$residential_maintenance_fee_field, " : "";
                        $new_line .= !empty($residential_maintenance_fee_option_field)? "$residential_maintenance_fee_option_field, " : "";
                        $new_line .= !empty($residential_sinking_fund_field)? "$residential_sinking_fund_field, " : "";
                        $new_line .= !empty($residential_sinking_fund_option_field)? "$residential_sinking_fund_option_field, " : "";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                    }
                } else {
                    $audit_fields_changed .= "<li> Residential : (". $residential_text . ")</li>";
                }
                if(($residentialExtra->count() > 0 && $draft_residential_extra->count() > 0) || ($residentialExtra->count() == 0 && $draft_residential_extra->count() > 0)
                || ($residentialExtra->count() > 0 && $draft_residential_extra->count() == 0)) {
                    $audit_fields_changed .= "<li>Residential Extra : (";
                    if(($residentialExtra->count() > 0 && $draft_residential_extra->count() > 0)) {
                        $check_residentialExtra_differents = Helper::check_diff_multi($residentialExtra->toArray(),$draft_residential_extra->toArray());
                        if(count($check_residentialExtra_differents)) {
                            $new_line = '';
                            foreach($check_residentialExtra_differents as $red_key => $red) {
                                if(is_array($red) && count($red)) {
                                    foreach($red as $red_data_key => $red_data) {
                                        if(!in_array($red_data_key, ['sort_no', 'id', 'updated_at'])) {
                                            $name = str_replace("_", " ", $red_data_key);
                                            $new_line .= $name . '=' . $red_data . ', ';
                                        }
                                    }
                                } else {
                                    if(!empty($red)) {
                                        $name = str_replace("_", " ", $red_key);
                                        $new_line .= $name . '=' . $red . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line);
                        }

                    } else if(($residentialExtra->count() == 0 && $draft_residential_extra->count() > 0)) {
                        $audit_fields_changed .= "new data";
                    } else {
                        $audit_fields_changed .= "remove residential extra data";
                    }
                    $audit_fields_changed .= ")</li>";
                }
                /** End Residential */
                /** Commercial */
                if(empty($commercial_text)) {
                    if(!empty($commercial_unit_no_field) || !empty($commercial_maintenance_fee_field) || !empty($commercial_maintenance_fee_option_field)
                    || !empty($commercial_sinking_fund_field) || !empty($commercial_sinking_fund_option_field)) {
                        $audit_fields_changed .= "<li> Commercial : (";
                        $new_line = '';
                        $new_line .= !empty($commercial_unit_no_field)? "$commercial_unit_no_field, " : "";
                        $new_line .= !empty($commercial_maintenance_fee_field)? "$commercial_maintenance_fee_field, " : "";
                        $new_line .= !empty($commercial_maintenance_fee_option_field)? "$commercial_maintenance_fee_option_field, " : "";
                        $new_line .= !empty($commercial_sinking_fund_field)? "$commercial_sinking_fund_field, " : "";
                        $new_line .= !empty($commercial_sinking_fund_option_field)? "$commercial_sinking_fund_option_field, " : "";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                    }
                } else {
                    $audit_fields_changed .= "<li> Commercial : (". $commercial_text . ")</li>";
                }
                if(($commercialExtra->count() > 0 && $draft_commercial_extra->count() > 0) || ($commercialExtra->count() == 0 && $draft_commercial_extra->count() > 0)
                || ($commercialExtra->count() > 0 && $draft_commercial_extra->count() == 0)) {
                    $audit_fields_changed .= "<li>Commercial Extra : (";
                    if(($commercialExtra->count() > 0 && $draft_commercial_extra->count() > 0)) {
                        $check_commercialExtra_differents = Helper::check_diff_multi($commercialExtra->toArray(),$draft_commercial_extra->toArray());
                        if(count($check_commercialExtra_differents)) {
                            $new_line = '';
                            foreach($check_commercialExtra_differents as $ced_key => $ced) {
                                if(is_array($ced) && count($ced)) {
                                    foreach($ced as $ced_data_key => $ced_data) {
                                        if(!in_array($ced_data_key, ['sort_no', 'id', 'updated_at'])) {
                                            $name = str_replace("_", " ", $ced_data_key);
                                            $new_line .= $name . '=' . $ced_data . ', ';
                                        }
                                    }
                                } else {
                                    if(!empty($ced)) {
                                        $name = str_replace("_", " ", $ced_key);
                                        $new_line .= $name . '=' . $ced . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line);
                        }

                    } else if(($commercialExtra->count() == 0 && $draft_commercial_extra->count() > 0)) {
                        $audit_fields_changed .= "new data";
                    } else {
                        $audit_fields_changed .= "remove commercial extra data";
                    }
                    $audit_fields_changed .= ")</li>";
                }
                /** End Commercial */
                /** Facility */
                if(!empty($facility_management_office_field) || !empty($facility_management_office_unit_field) || !empty($facility_swimming_pool_field)
                || !empty($facility_swimming_pool_unit_field) || !empty($facility_surau_field) || !empty($facility_surau_unit_field)
                || !empty($facility_surau_unit_field) || !empty($facility_multipurpose_hall_field) || !empty($facility_multipurpose_hall_unit_field)
                || !empty($facility_gym_field) || !empty($facility_gym_unit_field) || !empty($facility_playground_field)
                || !empty($facility_playground_unit_field) || !empty($facility_guardhouse_field) || !empty($facility_guardhouse_unit_field)
                || !empty($facility_kindergarten_field) || !empty($facility_kindergarten_unit_field) || !empty($facility_open_space_field)
                || !empty($facility_open_space_unit_field) || !empty($facility_lift_field) || !empty($facility_lift_unit_field)
                || !empty($facility_rubbish_room_field) || !empty($facility_rubbish_room_unit_field) || !empty($facility_gated_field)
                || !empty($facility_gated_unit_field) || !empty($facility_others_field)
                ) {
                    $audit_fields_changed .= "<li> Facility : (";
                    $new_line = '';
                    $new_line .= !empty($facility_management_office_field)? "$facility_management_office_field, " : "";
                    $new_line .= !empty($facility_management_office_unit_field)? "$facility_management_office_unit_field, " : "";
                    $new_line .= !empty($facility_swimming_pool_field)? "$facility_swimming_pool_field, " : "";
                    $new_line .= !empty($facility_swimming_pool_unit_field)? "$facility_swimming_pool_unit_field, " : "";
                    $new_line .= !empty($facility_surau_field)? "$facility_surau_field, " : "";
                    $new_line .= !empty($facility_surau_unit_field)? "$facility_surau_unit_field, " : "";
                    $new_line .= !empty($facility_multipurpose_field)? "$facility_multipurpose_field, " : "";
                    $new_line .= !empty($facility_multipurpose_unit_field)? "$facility_multipurpose_unit_field, " : "";
                    $new_line .= !empty($facility_gym_field)? "$facility_gym_field, " : "";
                    $new_line .= !empty($facility_gym_unit_field)? "$facility_gym_unit_field, " : "";
                    $new_line .= !empty($facility_playground_field)? "$facility_playground_field, " : "";
                    $new_line .= !empty($facility_playground_unit_field)? "$facility_playground_unit_field, " : "";
                    $new_line .= !empty($facility_guardhouse_field)? "$facility_guardhouse_field, " : "";
                    $new_line .= !empty($facility_guardhouse_unit_field)? "$facility_guardhouse_unit_field, " : "";
                    $new_line .= !empty($facility_kindergarten_field)? "$facility_kindergarten_field, " : "";
                    $new_line .= !empty($facility_kindergarten_unit_field)? "$facility_kindergarten_unit_field, " : "";
                    $new_line .= !empty($facility_open_space_field)? "$facility_open_space_field, " : "";
                    $new_line .= !empty($facility_open_space_unit_field)? "$facility_open_space_unit_field, " : "";
                    $new_line .= !empty($facility_lift_field)? "$facility_lift_field, " : "";
                    $new_line .= !empty($facility_lift_unit_field)? "$facility_lift_unit_field, " : "";
                    $new_line .= !empty($facility_rubbish_room_field)? "$facility_rubbish_room_field, " : "";
                    $new_line .= !empty($facility_rubbish_room_unit_field)? "$facility_rubbish_room_unit_field, " : "";
                    $new_line .= !empty($facility_gated_field)? "$facility_gated_field, " : "";
                    $new_line .= !empty($facility_gated_unit_field)? "$facility_gated_unit_field, " : "";
                    $new_line .= !empty($facility_others_field)? "$facility_others_field, " : "";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                }
                /** End Facility */
                $audit_fields_changed .= "</ul>";
                /** End Arrange audit fields changes */

                $strata->title = $draft_strata->title;
                $strata->name = $draft_strata->name;
                $strata->parliament = $draft_strata->parliament;
                $strata->dun = $draft_strata->dun;
                $strata->park = $draft_strata->park;
                $strata->address1 = $draft_strata->address1;
                $strata->address2 = $draft_strata->address2;
                $strata->address3 = $draft_strata->address3;
                $strata->address4 = $draft_strata->address4;
                $strata->poscode = $draft_strata->poscode;
                $strata->city = $draft_strata->city;
                $strata->state = $draft_strata->state;
                $strata->country = $draft_strata->country;
                $strata->block_no = $draft_strata->block_no;
                $strata->total_floor = $draft_strata->total_floor;
                $strata->year = $draft_strata->year;
                $strata->town = $draft_strata->town;
                $strata->area = $draft_strata->area;
                $strata->land_area = $draft_strata->land_area;
                $strata->total_share_unit = $draft_strata->total_share_unit;
                $strata->land_area_unit = $draft_strata->land_area_unit;
                $strata->lot_no = $draft_strata->lot_no;
                $strata->ownership_no = $draft_strata->ownership_no;
                $strata->date = $draft_strata->date;
                $strata->land_title = $draft_strata->land_title;
                $strata->category = $draft_strata->category;
                $strata->perimeter = $draft_strata->perimeter;
                $strata->ccc_no = $draft_strata->ccc_no;
                $strata->ccc_date = $draft_strata->ccc_date;
                $strata->file_url = $draft_strata->file_url;
                $strata->is_residential = $draft_strata->is_residential;
                $strata->is_commercial = $draft_strata->is_commercial;
                $strata->save();

                // clear draft
                $draft_strata->delete();

                //residential
                Residential::where('file_id', $files->id)->delete();
                ResidentialExtra::where('file_id', $files->id)->delete();
                if ($draft_residential && $strata->is_residential) {
                    $residential = new Residential();
                    $residential->file_id = $files->id;
                    $residential->strata_id = $strata->id;
                    $residential->unit_no = $draft_residential->unit_no;
                    $residential->maintenance_fee = $draft_residential->maintenance_fee;
                    $residential->maintenance_fee_option = $draft_residential->maintenance_fee_option;
                    $residential->sinking_fund = $draft_residential->sinking_fund;
                    $residential->sinking_fund_option = $draft_residential->sinking_fund_option;
                    $residential->save();

                    foreach($draft_residential_extra as $dr) {
                        $residential_extra = new ResidentialExtra();
                        $residential_extra->file_id = $files->id;
                        $residential_extra->strata_id = $strata->id;
                        $residential_extra->unit_no = $dr->unit_no;
                        $residential_extra->maintenance_fee = $dr->maintenance_fee;
                        $residential_extra->maintenance_fee_option = $dr->maintenance_fee_option;
                        $residential_extra->sinking_fund = $dr->sinking_fund;
                        $residential_extra->sinking_fund_option = $dr->sinking_fund_option;
                        $residential_extra->save();

                        $dr->delete();
                    }

                    // clear draft
                    $draft_residential->delete();
                }

                //commercial
                Commercial::where('file_id', $files->id)->delete();
                CommercialExtra::where('file_id', $files->id)->delete();
                if ($draft_commercial && $strata->is_commercial) {
                    $commercial = new Commercial();
                    $commercial->file_id = $files->id;
                    $commercial->strata_id = $strata->id;
                    $commercial->unit_no = $draft_commercial->unit_no;
                    $commercial->maintenance_fee = $draft_commercial->maintenance_fee;
                    $commercial->maintenance_fee_option = $draft_commercial->maintenance_fee_option;
                    $commercial->sinking_fund = $draft_commercial->sinking_fund;
                    $commercial->sinking_fund_option = $draft_commercial->sinking_fund_option;
                    $commercial->save();

                    foreach($draft_commercial_extra as $dc) {
                        $commercial_extra = new CommercialExtra();
                        $commercial_extra->file_id = $files->id;
                        $commercial_extra->strata_id = $strata->id;
                        $commercial_extra->unit_no = $dc->unit_no;
                        $commercial_extra->maintenance_fee = $dc->maintenance_fee;
                        $commercial_extra->maintenance_fee_option = $dc->maintenance_fee_option;
                        $commercial_extra->sinking_fund = $dc->sinking_fund;
                        $commercial_extra->sinking_fund_option = $dc->sinking_fund_option;
                        $commercial_extra->save();

                        $dc->delete();
                    }
                    // clear draft
                    $draft_commercial->delete();
                }

                //facility
                if ($draft_facility && !empty($draft_facility->reference_id)) {
                    $facility = Facility::findOrFail($draft_facility->reference_id);
                    $facility->file_id = $files->id;
                    $facility->strata_id = $strata->id;
                    $facility->management_office = $draft_facility->management_office;
                    $facility->management_office_unit = $draft_facility->management_office_unit;
                    $facility->swimming_pool = $draft_facility->swimming_pool;
                    $facility->swimming_pool_unit = $draft_facility->swimming_pool_unit;
                    $facility->surau = $draft_facility->surau;
                    $facility->surau_unit = $draft_facility->surau_unit;
                    $facility->multipurpose_hall = $draft_facility->multipurpose_hall;
                    $facility->multipurpose_hall_unit = $draft_facility->multipurpose_hall_unit;
                    $facility->gym = $draft_facility->gym;
                    $facility->gym_unit = $draft_facility->gym_unit;
                    $facility->playground = $draft_facility->playground;
                    $facility->playground_unit = $draft_facility->playground_unit;
                    $facility->guardhouse = $draft_facility->guardhouse;
                    $facility->guardhouse_unit = $draft_facility->guardhouse_unit;
                    $facility->kindergarten = $draft_facility->kindergarten;
                    $facility->kindergarten_unit = $draft_facility->kindergarten_unit;
                    $facility->open_space = $draft_facility->open_space;
                    $facility->open_space_unit = $draft_facility->open_space_unit;
                    $facility->lift = $draft_facility->lift;
                    $facility->lift_unit = $draft_facility->lift_unit;
                    $facility->rubbish_room = $draft_facility->rubbish_room;
                    $facility->rubbish_room_unit = $draft_facility->rubbish_room_unit;
                    $facility->gated = $draft_facility->gated;
                    $facility->gated_unit = $draft_facility->gated_unit;
                    $facility->others = $draft_facility->others;
                    $facility->save();

                    // clear draft
                    $draft_facility->delete();
                }

                if (!empty($strata->year)) {
                    $files->year = $strata->year;
                    $files->save();
                }

                // clear main draft
                $this->clearDraft($files);

                # Audit Trail
                if(!empty($audit_fields_changed)) {
                    $remarks = 'Strata Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }

                return "true";
            }
        }

        return "false";
    }

    public function management($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail(Helper::decode($id));

            if (!$files->management->draft) {
                return Redirect::to('/draft/others/' . Helper::encode($files->id));
            }

            $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            $management = Management::where('file_id', $files->id)->first();
            $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $agent = Agent::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'city' => $city,
                'country' => $country,
                'state' => $state,
                'file' => $files,
                'agent' => $agent,
                'house_scheme' => $house_scheme,
                'management' => $management,
                'image' => (!empty($files->other->image_url) ? $files->other->image_url : '')
            );

            return View::make('draft.management', $viewData);
        }

        return $this->errorPage();
    }

    public function submitManagement() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $draft_management = ManagementDraft::where('file_id', $files->id)->first();
            $draft_developer = ManagementDeveloperDraft::where('file_id', $files->id)->first();
            $draft_jmb = ManagementJMBDraft::where('file_id', $files->id)->first();
            $draft_mc = ManagementMCDraft::where('file_id', $files->id)->first();
            $draft_agent = ManagementAgentDraft::where('file_id', $files->id)->first();
            $draft_others = ManagementOthersDraft::where('file_id', $files->id)->first();
            $current_developer = ManagementDeveloper::where('file_id', $files->id)->first();
            $current_jmb = ManagementJMB::where('file_id', $files->id)->first();
            $current_mc = ManagementMC::where('file_id', $files->id)->first();
            $current_agent = ManagementAgent::where('file_id', $files->id)->first();
            $current_others = ManagementOthers::where('file_id', $files->id)->first();

            $audit_fields_changed = '';
            if ($draft_management && !empty($draft_management->reference_id)) {
                // management
                $management = Management::findOrFail($draft_management->reference_id);

                /** Arrange audit fields changes */
                $is_developer_field = $management->is_developer == $draft_management->is_developer? "": "management developer";
                $is_jmb_field = $management->is_jmb == $draft_management->is_jmb? "": "management jmb";
                $is_mc_field = $management->is_mc == $draft_management->is_mc? "": "management mc";
                $is_agent_field = $management->is_agent == $draft_management->is_agent? "": "management agent";
                $is_others_field = $management->is_others == $draft_management->is_others? "": "management others";

                if(!empty($is_developer_field) || !empty($is_jmb_field) || !empty($is_mc_field) || !empty($is_agent_field) || !empty($is_others_field)) {
                    $audit_fields_changed .= "<br><ul>";
                }
                /** Developer */
                if(!empty($is_developer_field)) {
                    if($draft_management->is_developer) {
                        $audit_fields_changed .= "<li> Developer : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Developer : data removed </li>";
                    }
                } else {
                    if($management->is_developer) {
                        /** Data Updated */
                        $new_line = '';
                        $new_line .= $current_developer->name != $draft_developer->name? "name, " : "";
                        $new_line .= $current_developer->address_1 != $draft_developer->address_1? "address 1, " : "";
                        $new_line .= $current_developer->address_2 != $draft_developer->address_2? "address 2, " : "";
                        $new_line .= $current_developer->address_3 != $draft_developer->address_3? "address 3, " : "";
                        $new_line .= $current_developer->address_4 != $draft_developer->address_4? "address 4, " : "";
                        $new_line .= $current_developer->city != $draft_developer->city? "city, " : "";
                        $new_line .= $current_developer->poscode != $draft_developer->poscode? "poscode, " : "";
                        $new_line .= $current_developer->state != $draft_developer->state? "state, " : "";
                        $new_line .= $current_developer->country != $draft_developer->country? "country, " : "";
                        $new_line .= $current_developer->phone_no != $draft_developer->phone_no? "phone no, " : "";
                        $new_line .= $current_developer->fax_no != $draft_developer->fax_no? "fax no, " : "";
                        $new_line .= $current_developer->remarks != $draft_developer->remarks? "remarks, " : "";
                        if(!empty($new_line)) {
                            $audit_fields_changed .= "<li> Developer : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                        }
                    }
                }
                /** JMB */
                if(!empty($is_jmb_field)) {
                    if($draft_management->is_jmb) {
                        $audit_fields_changed .= "<li> JMB : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> JMB : data removed </li>";
                    }
                } else {
                    if($management->is_jmb) {
                        /** Data Updated */
                        $new_line = '';
                        $new_line .= $current_jmb->date_formed != $draft_jmb->date_formed? "date formed, " : "";
                        $new_line .= $current_jmb->certificate_no != $draft_jmb->certificate_no? "certificate no, " : "";
                        $new_line .= $current_jmb->name != $draft_jmb->name? "name, " : "";
                        $new_line .= $current_jmb->address_1 != $draft_jmb->address_1? "address 1, " : "";
                        $new_line .= $current_jmb->address_2 != $draft_jmb->address_2? "address 2, " : "";
                        $new_line .= $current_jmb->address_3 != $draft_jmb->address_3? "address 3, " : "";
                        $new_line .= $current_jmb->city != $draft_jmb->city? "city, " : "";
                        $new_line .= $current_jmb->poscode != $draft_jmb->poscode? "poscode, " : "";
                        $new_line .= $current_jmb->state != $draft_jmb->state? "state, " : "";
                        $new_line .= $current_jmb->country != $draft_jmb->country? "country, " : "";
                        $new_line .= $current_jmb->phone_no != $draft_jmb->phone_no? "phone no, " : "";
                        $new_line .= $current_jmb->fax_no != $draft_jmb->fax_no? "fax no, " : "";
                        $new_line .= $current_jmb->email != $draft_jmb->email? "email, " : "";
                        if(!empty($new_line)) {
                            $audit_fields_changed .= "<li> JMB : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                        }
                    }
                }
                /** MC */
                if(!empty($is_mc_field)) {
                    if($draft_management->is_mc) {
                        $audit_fields_changed .= "<li> MC : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> MC : data removed </li>";
                    }
                } else {
                    if($management->is_mc) {
                        /** Data Updated */
                        $new_line = '';
                        $new_line .= $current_mc->date_formed != $draft_mc->date_formed? "date formed, " : "";
                        $new_line .= $current_mc->certificate_no != $draft_mc->certificate_no? "certificate no, " : "";
                        $new_line .= $current_mc->first_agm != $draft_mc->first_agm? "first agm, " : "";
                        $new_line .= $current_mc->name != $draft_mc->name? "name, " : "";
                        $new_line .= $current_mc->address_1 != $draft_mc->address_1? "address 1, " : "";
                        $new_line .= $current_mc->address_2 != $draft_mc->address_2? "address 2, " : "";
                        $new_line .= $current_mc->address_3 != $draft_mc->address_3? "address 3, " : "";
                        $new_line .= $current_mc->city != $draft_mc->city? "city, " : "";
                        $new_line .= $current_mc->poscode != $draft_mc->poscode? "poscode, " : "";
                        $new_line .= $current_mc->state != $draft_mc->state? "state, " : "";
                        $new_line .= $current_mc->country != $draft_mc->country? "country, " : "";
                        $new_line .= $current_mc->phone_no != $draft_mc->phone_no? "phone no, " : "";
                        $new_line .= $current_mc->fax_no != $draft_mc->fax_no? "fax no, " : "";
                        $new_line .= $current_mc->email != $draft_mc->email? "email, " : "";
                        if(!empty($new_line)) {
                            $audit_fields_changed .= "<li> MC : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                        }
                    }
                }
                /** Agent */
                if(!empty($is_agent_field)) {
                    if($draft_management->is_agent) {
                        $audit_fields_changed .= "<li> Agent : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Agent : data removed </li>";
                    }
                } else {
                    if($management->is_agent) {
                        /** Data Updated */
                        $new_line = '';
                        $new_line .= $current_agent->selected_by != $draft_agent->selected_by? "appointed by, " : "";
                        $new_line .= $current_agent->agent != $draft_agent->agent? "name, " : "";
                        $new_line .= $current_agent->address_1 != $draft_agent->address_1? "address 1, " : "";
                        $new_line .= $current_agent->address_2 != $draft_agent->address_2? "address 2, " : "";
                        $new_line .= $current_agent->address_3 != $draft_agent->address_3? "address 3, " : "";
                        $new_line .= $current_agent->city != $draft_agent->city? "city, " : "";
                        $new_line .= $current_agent->poscode != $draft_agent->poscode? "poscode, " : "";
                        $new_line .= $current_agent->state != $draft_agent->state? "state, " : "";
                        $new_line .= $current_agent->country != $draft_agent->country? "country, " : "";
                        $new_line .= $current_agent->phone_no != $draft_agent->phone_no? "phone no, " : "";
                        $new_line .= $current_agent->fax_no != $draft_agent->fax_no? "fax no, " : "";
                        $new_line .= $current_agent->email != $draft_agent->email? "email, " : "";
                        if(!empty($new_line)) {
                            $audit_fields_changed .= "<li> Agent : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                        }
                    }
                }
                /** Others */
                if(!empty($is_others_field)) {
                    if($draft_management->is_others) {
                        $audit_fields_changed .= "<li> Others : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Others : data removed </li>";
                    }
                } else {
                    if($management->is_others) {
                        /** Data Updated */
                        $new_line = '';
                        $new_line .= $current_others->agent != $draft_others->agent? "name, " : "";
                        $new_line .= $current_others->address_1 != $draft_others->address_1? "address 1, " : "";
                        $new_line .= $current_others->address_2 != $draft_others->address_2? "address 2, " : "";
                        $new_line .= $current_others->address_3 != $draft_others->address_3? "address 3, " : "";
                        $new_line .= $current_others->city != $draft_others->city? "city, " : "";
                        $new_line .= $current_others->poscode != $draft_others->poscode? "poscode, " : "";
                        $new_line .= $current_others->state != $draft_others->state? "state, " : "";
                        $new_line .= $current_others->country != $draft_others->country? "country, " : "";
                        $new_line .= $current_others->phone_no != $draft_others->phone_no? "phone no, " : "";
                        $new_line .= $current_others->fax_no != $draft_others->fax_no? "fax no, " : "";
                        $new_line .= $current_others->email != $draft_others->email? "email, " : "";
                        if(!empty($new_line)) {
                            $audit_fields_changed .= "<li> Others : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li>";
                        }
                    }
                }
                if(!empty($is_developer_field) || !empty($is_jmb_field) || !empty($is_mc_field) || !empty($is_agent_field) || !empty($is_others_field)) {
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                $management->is_developer = $draft_management->is_developer;
                $management->is_jmb = $draft_management->is_jmb;
                $management->is_mc = $draft_management->is_mc;
                $management->is_agent = $draft_management->is_agent;
                $management->is_others = $draft_management->is_others;
                $management->save();

                // clear draft
                $draft_management->delete();

                // developer
                ManagementDeveloper::where('file_id', $files->id)->delete();
                if ($draft_developer && $management->is_developer) {
                    $developer = new ManagementDeveloper();
                    $developer->file_id = $files->id;
                    $developer->management_id = $management->id;
                    $developer->name = $draft_developer->name;
                    $developer->address_1 = $draft_developer->address_1;
                    $developer->address_2 = $draft_developer->address_2;
                    $developer->address_3 = $draft_developer->address_3;
                    $developer->address_4 = $draft_developer->address_4;
                    $developer->city = $draft_developer->city;
                    $developer->poscode = $draft_developer->poscode;
                    $developer->state = $draft_developer->state;
                    $developer->country = $draft_developer->country;
                    $developer->phone_no = $draft_developer->phone_no;
                    $developer->fax_no = $draft_developer->fax_no;
                    $developer->remarks = $draft_developer->remarks;
                    $developer->save();

                    // clear draft
                    $draft_developer->delete();
                }

                // jmb
                ManagementJMB::where('file_id', $files->id)->delete();
                if ($draft_jmb && $management->is_jmb) {
                    $jmb = new ManagementJMB();
                    $jmb->file_id = $files->id;
                    $jmb->management_id = $management->id;
                    $jmb->date_formed = $draft_jmb->date_formed;
                    $jmb->certificate_no = $draft_jmb->certificate_no;
                    $jmb->name = $draft_jmb->name;
                    $jmb->address1 = $draft_jmb->address1;
                    $jmb->address2 = $draft_jmb->address2;
                    $jmb->address3 = $draft_jmb->address3;
                    $jmb->city = $draft_jmb->city;
                    $jmb->poscode = $draft_jmb->poscode;
                    $jmb->state = $draft_jmb->state;
                    $jmb->country = $draft_jmb->country;
                    $jmb->phone_no = $draft_jmb->phone_no;
                    $jmb->fax_no = $draft_jmb->fax_no;
                    $jmb->email = $draft_jmb->email;
                    $jmb->save();

                    // clear draft
                    $draft_jmb->delete();
                }

                // mc
                ManagementMC::where('file_id', $files->id)->delete();
                if ($draft_mc && $management->is_mc) {
                    $mc = new ManagementMC();
                    $mc->file_id = $files->id;
                    $mc->management_id = $management->id;
                    $mc->date_formed = $draft_mc->date_formed;
                    $mc->certificate_no = $draft_mc->certificate_no;
                    $mc->first_agm = $draft_mc->first_agm;
                    $mc->name = $draft_mc->name;
                    $mc->address1 = $draft_mc->address1;
                    $mc->address2 = $draft_mc->address2;
                    $mc->address3 = $draft_mc->address3;
                    $mc->city = $draft_mc->city;
                    $mc->poscode = $draft_mc->poscode;
                    $mc->state = $draft_mc->state;
                    $mc->country = $draft_mc->country;
                    $mc->phone_no = $draft_mc->phone_no;
                    $mc->fax_no = $draft_mc->fax_no;
                    $mc->email = $draft_mc->email;
                    $mc->save();

                    // clear draft
                    $draft_mc->delete();
                }

                // agent
                ManagementAgent::where('file_id', $files->id)->delete();
                if ($draft_agent && $management->is_agent) {
                    $agent = new ManagementAgent();
                    $agent->file_id = $files->id;
                    $agent->management_id = $management->id;
                    $agent->selected_by = $draft_agent->selected_by;
                    $agent->agent = $draft_agent->agent;
                    $agent->address1 = $draft_agent->address1;
                    $agent->address2 = $draft_agent->address2;
                    $agent->address3 = $draft_agent->address3;
                    $agent->city = $draft_agent->city;
                    $agent->poscode = $draft_agent->poscode;
                    $agent->state = $draft_agent->state;
                    $agent->country = $draft_agent->country;
                    $agent->phone_no = $draft_agent->phone_no;
                    $agent->fax_no = $draft_agent->fax_no;
                    $agent->email = $draft_agent->email;
                    $agent->save();

                    // clear draft
                    $draft_agent->delete();
                }

                // others
                ManagementOthers::where('file_id', $files->id)->delete();
                if ($draft_others && $management->is_others) {
                    $others = new ManagementOthers();
                    $others->file_id = $files->id;
                    $others->management_id = $management->id;
                    $others->name = $draft_others->name;
                    $others->address1 = $draft_others->address1;
                    $others->address2 = $draft_others->address2;
                    $others->address3 = $draft_others->address3;
                    $others->city = $draft_others->city;
                    $others->poscode = $draft_others->poscode;
                    $others->state = $draft_others->state;
                    $others->country = $draft_others->country;
                    $others->phone_no = $draft_others->phone_no;
                    $others->fax_no = $draft_others->fax_no;
                    $others->email = $draft_others->email;
                    $others->save();

                    // clear draft
                    $draft_others->delete();
                }
            }

            // clear main draft
            $this->clearDraft($files);

            # Audit Trail
            if(!empty($audit_fields_changed)) {
                $remarks = 'Management Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                $this->addAudit($files->id, "COB File", $remarks);
            }

            return "true";
        }

        return "false";
    }

    public function others($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail(Helper::decode($id));

            if (!$files->other->draft) {
                return Redirect::to('/draft/houseScheme/' . Helper::encode($files->id));
            }

            $other_details = OtherDetails::where('file_id', $files->id)->first();
            $tnbLists = OtherDetails::tnbLists();

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'file' => $files,
                'other_details' => $other_details,
                'image' => (!empty($files->other->image_url) ? $files->other->image_url : ''),
                'tnbLists' => $tnbLists
            );

            return View::make('draft.others', $viewData);
        }

        return $this->errorPage();
    }

    public function submitOthers() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $draft_others = OtherDetailsDraft::where('file_id', $files->id)->first();

            if ($draft_others && !empty($draft_others->reference_id)) {
                $others = OtherDetails::findOrFail($draft_others->reference_id);
                
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $others->name != $draft_others->name? "name, " : "";
                $new_line .= $others->image_url != $draft_others->image_url? "image url, " : "";
                $new_line .= $others->latitude != $draft_others->latitude? "latitude, " : "";
                $new_line .= $others->longitude != $draft_others->longitude? "longitude, " : "";
                $new_line .= $others->description != $draft_others->description? "description, " : "";
                $new_line .= $others->pms_system != $draft_others->pms_system? "pms system, " : "";
                $new_line .= $others->owner_occupied != $draft_others->owner_occupied? "owner occupied, " : "";
                $new_line .= $others->rented != $draft_others->rented? "rented, " : "";
                $new_line .= $others->bantuan_lphs != $draft_others->bantuan_lphs? "bantuan lphs, " : "";
                $new_line .= $others->bantuan_others != $draft_others->bantuan_others? "bantuan others, " : "";
                $new_line .= $others->rsku != $draft_others->rsku? "rsku, " : "";
                $new_line .= $others->water_meter != $draft_others->water_meter? "water meter, " : "";
                $new_line .= $others->tnb != $draft_others->tnb? "tnb, " : "";
                $new_line .= $others->malay_composition != $draft_others->malay_composition? "malay composition, " : "";
                $new_line .= $others->chinese_composition != $draft_others->chinese_composition? "chinese composition, " : "";
                $new_line .= $others->indian_composition != $draft_others->indian_composition? "indian composition, " : "";
                $new_line .= $others->others_composition != $draft_others->others_composition? "others composition, " : "";
                $new_line .= $others->foreigner_composition != $draft_others->foreigner_composition? "foreigner composition, " : "";
                if(!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Others : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) .")</li></ul>";
                }
                /** End Arrange audit fields changes */

                $others->name = $draft_others->name;
                $others->image_url = $draft_others->image_url;
                $others->latitude = $draft_others->latitude;
                $others->longitude = $draft_others->longitude;
                $others->description = $draft_others->description;
                $others->pms_system = $draft_others->pms_system;
                $others->owner_occupied = $draft_others->owner_occupied;
                $others->rented = $draft_others->rented;
                $others->bantuan_lphs = $draft_others->bantuan_lphs;
                $others->bantuan_others = $draft_others->bantuan_others;
                $others->rsku = $draft_others->rsku;
                $others->water_meter = $draft_others->water_meter;
                $others->tnb = $draft_others->tnb;
                $others->malay_composition = $draft_others->malay_composition;
                $others->chinese_composition = $draft_others->chinese_composition;
                $others->indian_composition = $draft_others->indian_composition;
                $others->others_composition = $draft_others->others_composition;
                $others->foreigner_composition = $draft_others->foreigner_composition;
                $others->save();

                // clear draft
                $draft_others->delete();

                // clear main draft
                $this->clearDraft($files);

                # Audit Trail
                if(!empty($audit_fields_changed)) {
                    $remarks = 'Others Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }

                return "true";
            }
        }

        return "false";
    }

    public function clearDraft($files) {
        if (!$files->hasDraft()) {
            $files->draft->is_deleted = true;
            $files->draft->save();
        }
    }

    public function deleteFile() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail(Helper::decode($data['file_id']));
            if ($files) {
                $files->draft->is_deleted = true;
                $files->draft->save();

                return "true";
            }
        }

        return "false";
    }

    public function errorPage() {
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
