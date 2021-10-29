<?php

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
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
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
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('file_drafts', 'files.id', '=', 'file_drafts.file_id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', '!=', 2)
                        ->where('files.is_deleted', 0)
                        ->where('file_drafts.is_deleted', 0);
            } else {
                $file = Files::join('file_drafts', 'files.id', '=', 'file_drafts.file_id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', '!=', 2)
                        ->where('files.is_deleted', 0)
                        ->where('file_drafts.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('file_drafts', 'files.id', '=', 'file_drafts.file_id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.is_active', '!=', 2)
                        ->where('files.is_deleted', 0)
                        ->where('file_drafts.is_deleted', 0);
            } else {
                $file = Files::join('file_drafts', 'files.id', '=', 'file_drafts.file_id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_active', '!=', 2)
                        ->where('files.is_deleted', 0)
                        ->where('file_drafts.is_deleted', 0);
            }
        }

        return Datatables::of($file)
                        ->addColumn('cob', function ($model) {
                            return ($model->company_id ? $model->company->short_name : '-');
                        })
                        ->editColumn('file_no', function ($model) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('DraftController@houseScheme', $model->id) . "'>" . $model->file_no . "</a>";
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
                            $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFile(\'' . $model->id . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

                            return $button;
                        })
                        ->make(true);
    }

    public function houseScheme($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail($id);

            if (!$files->hasDraft()) {
                return Redirect::to('/draft/fileList');
            } else if (!$files->houseScheme->draft) {
                return Redirect::to('/draft/strata/' . $files->id);
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
            $files = Files::findOrFail($data['file_id']);
            $draft_house = HouseSchemeDraft::where('file_id', $files->id)->where('is_deleted', 0)->first();

            if ($draft_house && !empty($draft_house->reference_id)) {
                $house_scheme = HouseScheme::findOrFail($draft_house->reference_id);
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
                $house_scheme->save();

                // clear draft
                $draft_house->delete();

                // clear main draft
                $this->clearDraft($files);

                # Audit Trail
                $remarks = 'House Info (' . $files->file_no . ') has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return "true";
            }
        }

        return "false";
    }

    public function strata($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail($id);

            if (!$files->strata->draft) {
                return Redirect::to('/draft/management/' . $files->id);
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
            $files = Files::findOrFail($data['file_id']);
            $draft_strata = StrataDraft::where('file_id', $files->id)->first();
            $draft_residential = ResidentialDraft::where('file_id', $files->id)->first();
            $draft_commercial = CommercialDraft::where('file_id', $files->id)->first();
            $draft_facility = FacilityDraft::where('file_id', $files->id)->first();

            if ($draft_strata && !empty($draft_strata->reference_id)) {
                // strata
                $strata = Strata::findOrFail($draft_strata->reference_id);
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

                    // clear draft
                    $draft_residential->delete();
                }

                //commercial
                Commercial::where('file_id', $files->id)->delete();
                if ($draft_commercial && $strata->is_commercial) {
                    $commercial = new Commercial();
                    $residential->file_id = $files->id;
                    $commercial->strata_id = $strata->id;
                    $commercial->unit_no = $draft_commercial->unit_no;
                    $commercial->maintenance_fee = $draft_commercial->maintenance_fee;
                    $commercial->maintenance_fee_option = $draft_commercial->maintenance_fee_option;
                    $commercial->sinking_fund = $draft_commercial->sinking_fund;
                    $commercial->sinking_fund_option = $draft_commercial->sinking_fund_option;
                    $commercial->save();

                    // clear draft
                    $draft_commercial->delete();
                }

                //facility
                if ($draft_facility && !empty($draft_facility->reference_id)) {
                    $facility = Facility::findOrFail($draft_facility->reference_id);
                    $residential->file_id = $files->id;
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
                $remarks = 'Strata Info (' . $files->file_no . ') has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return "true";
            }
        }

        return "false";
    }

    public function management($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail($id);

            if (!$files->management->draft) {
                return Redirect::to('/draft/others/' . $files->id);
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
            $files = Files::findOrFail($data['file_id']);
            $draft_management = ManagementDraft::where('file_id', $files->id)->first();
            $draft_developer = ManagementDeveloperDraft::where('file_id', $files->id)->first();
            $draft_jmb = ManagementJMBDraft::where('file_id', $files->id)->first();
            $draft_mc = ManagementMCDraft::where('file_id', $files->id)->first();
            $draft_agent = ManagementAgentDraft::where('file_id', $files->id)->first();
            $draft_others = ManagementOthersDraft::where('file_id', $files->id)->first();

            if ($draft_management && !empty($draft_management->reference_id)) {
                // management
                $management = Management::findOrFail($draft_management->reference_id);
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
                if ($draft_management && $management->is_agent) {
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
            $remarks = 'Management Info (' . $files->file_no . ') has been updated.';
            $auditTrail = new AuditTrail();
            $auditTrail->module = "COB File";
            $auditTrail->remarks = $remarks;
            $auditTrail->audit_by = Auth::user()->id;
            $auditTrail->save();

            return "true";
        }

        return "false";
    }

    public function others($id) {
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            $files = Files::findOrFail($id);

            if (!$files->other->draft) {
                return Redirect::to('/draft/houseScheme/' . $files->id);
            }

            $other_details = OtherDetails::where('file_id', $files->id)->first();

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_draft_list',
                'file' => $files,
                'other_details' => $other_details,
                'image' => (!empty($files->other->image_url) ? $files->other->image_url : '')
            );

            return View::make('draft.others', $viewData);
        }

        return $this->errorPage();
    }

    public function submitOthers() {
        $data = Input::all();
        if (Request::ajax()) {
            $files = Files::findOrFail($data['file_id']);
            $draft_others = OtherDetailsDraft::where('file_id', $files->id)->first();

            if ($draft_others && !empty($draft_others->reference_id)) {
                $others = OtherDetails::findOrFail($draft_others->reference_id);
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
                $remarks = 'Others Info (' . $files->file_no . ') has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

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
            $files = Files::find($data['file_id']);
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
