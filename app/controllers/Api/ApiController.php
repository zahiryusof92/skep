<?php

namespace Api;

use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use User;
use Files;
use Scoring;
use AuditTrail;
use Buyer;
use Document;
use Insurance;
use MeetingDocument;
use AJKDetails;
use HousingSchemeUser;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends BaseController {

    public function login() {
        $result = array();

        $username = Request::get('username');
        $password = Request::get('password');

        $auth = Auth::attempt(array(
                    'username' => $username,
                    'password' => $password,
                    'status' => 1,
                    'is_active' => 1,
                    'is_deleted' => 0,
                        ), false);

        if ($auth) {
            $user = User::find(Auth::user()->id);

            if ($user) {
                if ($user->getRole->name == 'JMB' || $user->getRole->name == 'MC') {
                    $current = strtotime(date('Y-m-d'));
                    $start = strtotime($user->start_date);
                    $end = strtotime($user->end_date);

                    if ($current < $start && $current > $end) {
                        Auth::logout();

                        $response = array(
                            'error' => true,
                            'message' => 'Account Expired',
                            'result' => false,
                        );

                        return Response::json($response);
                    }
                }

                // Audit Trail
                $remarks = 'User ' . $user->username . ' is signed using Mobile App';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = $user->id;
                $auditTrail->save();

                $result[] = array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'full_name' => ($user->full_name ? $user->full_name : ''),
                    'email' => ($user->email ? $user->email : ''),
                    'phone_no' => ($user->phone_no ? $user->phone_no : ''),
                    'role_id' => $user->role,
                    'role' => ($user->role ? $user->getRole->name : ''),
                    'company_id' => $user->company_id,
                    'company' => ($user->company_id ? $user->getCOB->name : ''),
                    'remarks' => ($user->remarks ? $user->remarks : ''),
                    'token' => JWTAuth::fromUser($user),
                    'created_at' => ($user->created_at ? $user->created_at->format('Y-m-d H:i:s') : ''),
                    'updated_at' => ($user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '')
                );

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Login Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function editprofile() {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $user->full_name = (Request::has('full_name') ? Request::get('full_name') : $user->full_name);
            $user->email = Request::has('email') ? Request::get('email') : $user->email;
            $user->phone_no = Request::has('phone_no') ? Request::get('phone_no') : $user->phone_no;
            $success = $user->save();

            if ($success) {
                // Audit Trail
                $remarks = 'User ' . $user->username . ' is updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Users";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = $user->id;
                $auditTrail->save();

                $result[] = array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'full_name' => ($user->full_name ? $user->full_name : ''),
                    'email' => ($user->email ? $user->email : ''),
                    'phone_no' => ($user->phone_no ? $user->phone_no : ''),
                    'role_id' => $user->role,
                    'role' => ($user->role ? $user->getRole->name : ''),
                    'company_id' => $user->company_id,
                    'company' => ($user->company_id ? $user->getCOB->name : ''),
                    'remarks' => ($user->remarks ? $user->remarks : ''),
                    'created_at' => ($user->created_at ? $user->created_at->format('Y-m-d H:i:s') : ''),
                    'updated_at' => ($user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '')
                );

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function files() {
        $result = array();
        $fileList = array();

        $total_files = 0;
        $current_page = ((Request::has('page') && !empty(Request::get('page'))) ? Request::get('page') : 1);
        $per_page = ((Request::has('per_page') && !empty(Request::get('per_page'))) ? Request::get('per_page') : 15);
        $from = ($current_page - 1) * $per_page;
        $order_raw = ((Request::has('order') && !empty(Request::has('order'))) ? Request::get('order') : 'file_name');
        $dir = ((Request::has('dir') && !empty(Request::has('dir'))) ? Request::get('dir') : 'asc');

        if ($order_raw && $order_raw == 'file_no') {
            $order = 'files.file_no';
        } else if ($order_raw && $order_raw == 'file_name') {
            $order = 'strata.name';
        } else if ($order_raw && $order_raw == 'company') {
            $order = 'company.short_name';
        } else if ($order_raw && $order_raw == 'year') {
            $order = 'strata.year';
        } else {
            $order = 'strata.name';
        }

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            if (!$user->getAdmin()) {
                if (!empty($user->file_id)) {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->where('files.id', $user->file_id)
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where('files.id', $user->file_id)
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                } else {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                } else {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                }
            }

            if ($files) {
                foreach ($files as $file) {
                    $fileList[] = array(
                        'id' => $file->id,
                        'company_id' => $file->company_id,
                        'company' => ($file->company_id ? $file->company->short_name : ''),
                        'file_no' => ($file->file_no ? $file->file_no : ''),
                        'file_name' => ($file->strata_id ? $file->strata->name : ''),
                        'year' => (($file->strata_id && $file->strata->year > 0) ? $file->strata->year : ''),
                        'remarks' => ($file->remarks ? $file->remarks : ''),
                        'created_at' => ($file->created_at ? $file->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->updated_at ? $file->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }
            }

            $result[] = array(
                'oder' => $order_raw,
                'dir' => $dir,
                'page' => ceil($current_page),
                'total' => $total_files,
                'per_page' => $per_page,
                'last_page' => ceil($total_files / $per_page),
                'from' => $from + 1,
                'to' => ($current_page * $per_page) < $total_files ? ($current_page * $per_page) : $total_files,
                'data' => $fileList
            );

            $response = array(
                'error' => false,
                'message' => 'Success',
                'result' => $result,
            );

            return Response::json($response);
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function houseScheme() {
        $result = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->houseScheme) {
                    $result[] = array(
                        'id' => $file->houseScheme->id,
                        'name' => $file->houseScheme->name,
                        'developer_id' => $file->houseScheme->developer,
                        'developer' => ($file->houseScheme->developer ? $file->houseScheme->developers->name : ''),
                        'address1' => $file->houseScheme->address1,
                        'address2' => $file->houseScheme->address2,
                        'address3' => $file->houseScheme->address3,
                        'address4' => $file->houseScheme->address4,
                        'poscode' => $file->houseScheme->poscode,
                        'city_id' => $file->houseScheme->city,
                        'city' => ($file->houseScheme->city ? $file->houseScheme->cities->description : ''),
                        'state_id' => $file->houseScheme->state,
                        'state' => ($file->houseScheme->state ? $file->houseScheme->states->name : ''),
                        'country_id' => $file->houseScheme->country,
                        'country' => ($file->houseScheme->country ? $file->houseScheme->countries->name : ''),
                        'phone_no' => $file->houseScheme->phone_no,
                        'fax_no' => $file->houseScheme->fax_no,
                        'remarks' => ($file->houseScheme->remarks ? $file->houseScheme->remarks : ''),
                        'created_at' => ($file->houseScheme->created_at ? $file->houseScheme->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->houseScheme->updated_at ? $file->houseScheme->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function personInCharge() {
        $result = array();
        $dataList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total = HousingSchemeUser::where('file_id', $file_id)
                        ->where('is_deleted', 0)
                        ->count();

                $data = HousingSchemeUser::where('file_id', $file_id)
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($data) {
                    foreach ($data as $incharge) {
                        $dataList[] = array(
                            'id' => $incharge->id,
                            'user_id' => $incharge->user_id,
                            'name' => ($incharge->user_id ? $incharge->user->full_name : ''),
                            'phone_no' => ($incharge->user_id ? $incharge->user->phone_no : ''),
                            'email' => ($incharge->user_id ? $incharge->user->email : ''),
                            'created_at' => ($incharge->created_at ? $incharge->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($incharge->updated_at ? $incharge->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total ? ($current_page * $per_page) : $total,
                        'data' => $dataList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function strata() {
        $result = array();
        $residential = '';
        $commercial = '';

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->strata) {
                    if ($file->strata->is_residential) {
                        $residential = array(
                            'id' => $file->resident->id,
                            'unit_no' => ($file->resident->unit_no ? $file->resident->unit_no : ''),
                            'maintenance_fee' => ($file->resident->maintenance_fee ? $file->resident->maintenance_fee : ''),
                            'maintenance_fee_option' => ($file->resident->maintenance_fee_option ? $file->resident->mfUnit->description : ''),
                            'sinking_fund' => ($file->resident->sinking_fund ? $file->resident->sinking_fund : ''),
                            'sinking_fund_option' => ($file->resident->sinking_fund_option ? $file->resident->sfUnit->description : ''),
                            'created_at' => ($file->resident->created_at ? $file->resident->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->resident->updated_at ? $file->resident->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    if ($file->strata->is_commercial) {
                        $commercial = array(
                            'id' => $file->commercial->id,
                            'unit_no' => ($file->commercial->unit_no ? $file->commercial->unit_no : ''),
                            'maintenance_fee' => ($file->commercial->maintenance_fee ? $file->commercial->maintenance_fee : ''),
                            'maintenance_fee_option' => ($file->commercial->maintenance_fee_option ? $file->commercial->mfUnit->description : ''),
                            'sinking_fund' => ($file->commercial->sinking_fund ? $file->commercial->sinking_fund : ''),
                            'sinking_fund_option' => ($file->commercial->sinking_fund_option ? $file->commercial->sfUnit->description : ''),
                            'created_at' => ($file->commercial->created_at ? $file->commercial->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->commercial->updated_at ? $file->commercial->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'id' => $file->strata->id,
                        'name' => ($file->strata->name ? $file->strata->name : ''),
                        'title' => ($file->strata->title ? true : false),
                        'parliament_id' => $file->strata->parliament,
                        'parliament' => ($file->strata->parliament ? $file->strata->parliment->description : ''),
                        'dun_id' => $file->strata->dun,
                        'dun' => ($file->strata->dun ? $file->strata->duns->description : ''),
                        'park_id' => $file->strata->park,
                        'park' => ($file->strata->park ? $file->strata->parks->description : ''),
                        'address1' => ($file->strata->address1 ? $file->strata->address1 : ''),
                        'address2' => ($file->strata->address2 ? $file->strata->address2 : ''),
                        'address3' => ($file->strata->address3 ? $file->strata->address3 : ''),
                        'address4' => ($file->strata->address4 ? $file->strata->address4 : ''),
                        'city_id' => $file->strata->city,
                        'city' => ($file->strata->city ? $file->strata->cities->description : ''),
                        'poscode' => ($file->strata->poscode ? $file->strata->poscode : ''),
                        'state_id' => $file->strata->state,
                        'state' => ($file->strata->state ? $file->strata->states->name : ''),
                        'country_id' => $file->strata->country,
                        'country' => ($file->strata->country ? $file->strata->countries->name : ''),
                        'block_no' => ($file->strata->block_no ? $file->strata->block_no : ''),
                        'total_floor' => ($file->strata->total_floor ? $file->strata->total_floor : ''),
                        'year' => ((!empty($file->strata->year) && $file->strata->year > 0) ? $file->strata->year : ''),
                        'ownership_no' => ($file->strata->ownership_no ? $file->strata->ownership_no : ''),
                        'town_id' => $file->strata->town,
                        'town' => ($file->strata->town ? $file->strata->towns->description : ''),
                        'area_id' => $file->strata->area,
                        'area' => ($file->strata->area ? $file->strata->areas->description : ''),
                        'land_area' => $file->strata->land_area,
                        'land_area_unit_id' => $file->strata->land_area_unit,
                        'land_area_unit' => ($file->strata->land_area_unit ? $file->strata->areaUnit->description : ''),
                        'lot_no' => ($file->strata->lot_no ? $file->strata->lot_no : ''),
                        'date' => ($file->strata->date != '0000-00-00' ? $file->strata->date : ''),
                        'land_title_id' => $file->strata->land_title,
                        'land_title' => ($file->strata->land_title ? $file->strata->landTitle->description : ''),
                        'category_id' => $file->strata->category,
                        'category' => ($file->strata->category ? $file->strata->categories->description : ''),
                        'perimeter_id' => $file->strata->perimeter,
                        'perimeter' => ($file->strata->perimeter ? $file->strata->perimeters->id : ''),
                        'file_url' => ($file->strata->url ? asset($file->strata->url) : ''),
                        'total_share_unit' => ($file->strata->total_share_unit ? $file->strata->total_share_unit : ''),
                        'ccc_no' => ($file->strata->ccc_no ? $file->strata->ccc_no : ''),
                        'ccc_date' => ($file->strata->ccc_date != '0000-00-00' ? $file->strata->ccc_date : ''),
                        'is_residential' => ($file->strata->is_residential ? true : false),
                        'residential' => $residential,
                        'is_commercial' => ($file->strata->is_commercial ? true : false),
                        'commercial' => $commercial,
                        'created_at' => ($file->strata->created_at ? $file->strata->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->strata->updated_at ? $file->strata->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function facility() {
        $result = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->facility) {
                    $result[] = array(
                        'id' => $file->facility->id,
                        'management_office' => ($file->facility->management_office ? true : false),
                        'management_office_unit' => ($file->facility->management_office_unit ? $file->facility->management_office_unit : 0),
                        'swimming_pool' => ($file->facility->swimming_pool ? true : false),
                        'swimming_pool_unit' => ($file->facility->swimming_pool_unit ? $file->facility->swimming_pool_unit : 0),
                        'surau' => ($file->facility->surau ? true : false),
                        'surau_unit' => ($file->facility->surau_unit ? $file->facility->surau_unit : 0),
                        'multipurpose_hall' => ($file->facility->multipurpose_hall ? true : false),
                        'multipurpose_hall_unit' => ($file->facility->multipurpose_hall_unit ? $file->facility->multipurpose_hall_unit : 0),
                        'gym' => ($file->facility->gym ? true : false),
                        'gym_unit' => ($file->facility->gym_unit ? $file->facility->gym_unit : 0),
                        'playground' => ($file->facility->playground ? true : false),
                        'playground_unit' => ($file->facility->playground_unit ? $file->facility->playground_unit : 0),
                        'guardhouse' => ($file->facility->guardhouse ? true : false),
                        'guardhouse_unit' => ($file->facility->guardhouse_unit ? $file->facility->guardhouse_unit : 0),
                        'kindergarten' => ($file->facility->kindergarten ? true : false),
                        'kindergarten_unit' => ($file->facility->kindergarten_unit ? $file->facility->kindergarten_unit : 0),
                        'open_space' => ($file->facility->open_space ? true : false),
                        'open_space_unit' => ($file->facility->open_space_unit ? $file->facility->open_space_unit : 0),
                        'lift' => ($file->facility->lift ? true : false),
                        'lift_unit' => ($file->facility->lift_unit ? $file->facility->lift_unit : 0),
                        'rubbish_room' => ($file->facility->rubbish_room ? true : false),
                        'rubbish_room_unit' => ($file->facility->rubbish_room_unit ? $file->facility->rubbish_room_unit : 0),
                        'gated' => ($file->facility->gated ? true : false),
                        'gated_unit' => ($file->facility->gated_unit ? $file->facility->gated_unit : 0),
                        'others' => ($file->facility->others ? $file->facility->others : ''),
                        'created_at' => ($file->other->created_at ? $file->other->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->other->updated_at ? $file->other->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function management() {
        $result = array();
        $jmb = array();
        $mc = array();
        $agent = array();
        $others = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->management) {
                    if ($file->management->is_jmb && $file->managementJMB) {
                        $jmb = array(
                            'id' => $file->managementJMB->id,
                            'date_formed' => ($file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                            'certificate_no' => ($file->managementJMB->certificate_no ? $file->managementJMB->certificate_no : ''),
                            'name' => ($file->managementJMB->name ? $file->managementJMB->name : ''),
                            'address1' => ($file->managementJMB->address1 ? $file->managementJMB->address1 : ''),
                            'address2' => ($file->managementJMB->address2 ? $file->managementJMB->address2 : ''),
                            'address3' => ($file->managementJMB->address3 ? $file->managementJMB->address3 : ''),
                            'address4' => ($file->managementJMB->address4 ? $file->managementJMB->address4 : ''),
                            'city_id' => $file->managementJMB->city,
                            'city' => ($file->managementJMB->city ? $file->managementJMB->cities->description : ''),
                            'poscode' => ($file->managementJMB->poscode ? $file->managementJMB->poscode : ''),
                            'state_id' => $file->managementJMB->state,
                            'state' => ($file->managementJMB->state ? $file->managementJMB->states->name : ''),
                            'country_id' => $file->managementJMB->country,
                            'country' => ($file->managementJMB->country ? $file->managementJMB->countries->name : ''),
                            'phone_no' => ($file->managementJMB->phone_no ? $file->managementJMB->phone_no : ''),
                            'fax_no' => ($file->managementJMB->fax_no ? $file->managementJMB->fax_no : ''),
                            'email' => ($file->managementJMB->email ? $file->managementJMB->email : ''),
                            'created_at' => ($file->managementJMB->created_at ? $file->managementJMB->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->managementJMB->created_at ? $file->managementJMB->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    if ($file->management->is_mc && $file->managementMC) {
                        $mc = array(
                            'id' => $file->managementMC->id,
                            'date_formed' => ($file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                            'certificate_no' => $file->managementMC->certificate_no,
                            'first_agm' => ($file->managementMC->first_agm != '0000-00-00' ? $file->managementMC->first_agm : ''),
                            'name' => ($file->managementMC->name ? $file->managementMC->name : ''),
                            'address1' => ($file->managementMC->address1 ? $file->managementMC->address1 : ''),
                            'address2' => ($file->managementMC->address2 ? $file->managementMC->address2 : ''),
                            'address3' => ($file->managementMC->address3 ? $file->managementMC->address3 : ''),
                            'address4' => ($file->managementMC->address4 ? $file->managementMC->address4 : ''),
                            'city_id' => $file->managementMC->city,
                            'city' => ($file->managementMC->city ? $file->managementMC->cities->description : ''),
                            'poscode' => $file->managementMC->poscode,
                            'state_id' => $file->managementMC->state,
                            'state' => ($file->managementMC->state ? $file->managementMC->states->name : ''),
                            'country_id' => $file->managementMC->country,
                            'country' => ($file->managementMC->country ? $file->managementMC->countries->name : ''),
                            'phone_no' => $file->managementMC->phone_no,
                            'fax_no' => $file->managementMC->fax_no,
                            'email' => $file->managementMC->email,
                            'created_at' => ($file->managementMC->created_at ? $file->managementMC->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->managementMC->created_at ? $file->managementMC->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    if ($file->management->is_agent && $file->managementAgent) {
                        $agent = array(
                            'id' => $file->managementAgent->id,
                            'selected_by' => ($file->managementAgent->selected_by ? $file->managementAgent->selected_by : ''),
                            'agent' => ($file->managementAgent->agent ? $file->managementAgent->agent : ''),
                            'address1' => ($file->managementAgent->address1 ? $file->managementAgent->address1 : ''),
                            'address2' => ($file->managementAgent->address2 ? $file->managementAgent->address2 : ''),
                            'address3' => ($file->managementAgent->address3 ? $file->managementAgent->address3 : ''),
                            'address4' => ($file->managementAgent->address4 ? $file->managementAgent->address4 : ''),
                            'city_id' => $file->managementAgent->city,
                            'city' => ($file->managementAgent->city ? $file->managementAgent->cities->description : ''),
                            'poscode' => ($file->managementAgent->poscode ? $file->managementAgent->poscode : ''),
                            'state_id' => $file->managementAgent->state,
                            'state' => ($file->managementAgent->state ? $file->managementAgent->states->name : ''),
                            'country_id' => $file->managementAgent->country,
                            'country' => ($file->managementAgent->country ? $file->managementAgent->countries->name : ''),
                            'phone_no' => ($file->managementAgent->phone_no ? $file->managementAgent->phone_no : ''),
                            'fax_no' => ($file->managementAgent->fax_no ? $file->managementAgent->fax_no : ''),
                            'email' => ($file->managementAgent->email ? $file->managementAgent->email : ''),
                            'created_at' => ($file->managementAgent->created_at ? $file->managementAgent->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->managementAgent->created_at ? $file->managementAgent->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    if ($file->management->is_others && $file->managementOthers) {
                        $others = array(
                            'id' => $file->managementOthers->id,
                            'name' => ($file->managementOthers->name ? $file->managementOthers->name : ''),
                            'address1' => ($file->managementOthers->address1 ? $file->managementOthers->address1 : ''),
                            'address2' => ($file->managementOthers->address2 ? $file->managementOthers->address2 : ''),
                            'address3' => ($file->managementOthers->address3 ? $file->managementOthers->address3 : ''),
                            'address4' => ($file->managementOthers->address4 ? $file->managementOthers->address4 : ''),
                            'city_id' => $file->managementOthers->city,
                            'city' => ($file->managementOthers->city ? $file->managementOthers->cities->description : ''),
                            'poscode' => ($file->managementOthers->poscode ? $file->managementOthers->poscode : ''),
                            'state_id' => $file->managementOthers->state,
                            'state' => ($file->managementOthers->state ? $file->managementOthers->states->name : ''),
                            'country_id' => $file->managementOthers->country,
                            'country' => ($file->managementOthers->country ? $file->managementOthers->countries->name : ''),
                            'phone_no' => ($file->managementOthers->phone_no ? $file->managementOthers->phone_no : ''),
                            'fax_no' => ($file->managementOthers->fax_no ? $file->managementOthers->fax_no : ''),
                            'email' => ($file->managementOthers->email ? $file->managementOthers->email : ''),
                            'created_at' => ($file->managementOthers->created_at ? $file->managementOthers->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->managementOthers->created_at ? $file->managementOthers->updated_at->format('Y-m-d H:i:s') : '')
                        );

                        $result[] = array(
                            'id' => $file->management->id,
                            'is_jmb' => ($file->management->is_jmb ? true : false),
                            'jmb' => $jmb,
                            'is_mc' => ($file->management->is_mc ? true : false),
                            'mc' => $mc,
                            'is_agent' => ($file->management->is_agent ? true : false),
                            'agent' => $agent,
                            'is_others' => ($file->management->is_others ? true : false),
                            'others' => $others,
                            'created_at' => ($file->management->created_at ? $file->management->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($file->management->created_at ? $file->management->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function monitoring() {
        $result = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->monitoring) {
                    $result[] = array(
                        'id' => $file->monitoring->id,
                        'pre_calculate' => ($file->monitoring->pre_calculate ? true : false),
                        'buyer_registration' => ($file->monitoring->buyer_registration ? true : false),
                        'certificate_no' => ($file->monitoring->certificate_no ? $file->monitoring->certificate_no : ''),
                        'remarks' => ($file->monitoring->remarks ? $file->monitoring->remarks : ''),
                        'created_at' => ($file->monitoring->created_at ? $file->monitoring->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->monitoring->created_at ? $file->monitoring->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function meetingJMB() {
        $result = array();
        $meetingList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_meetings = MeetingDocument::where('file_id', $file->id)
                        ->where('type', 'jmb')
                        ->where('is_deleted', 0)
                        ->count();

                $meetings = MeetingDocument::where('file_id', $file->id)
                        ->where('type', 'jmb')
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($meetings) {
                    foreach ($meetings as $meeting) {
                        $meetingList[] = array(
                            'id' => $meeting->id,
                            'agm_date' => ($meeting->agm_date ? $meeting->agm_date : ''),
                            'agm' => ($meeting->agm ? true : false),
                            'agm_file_url' => ($meeting->agm_file_url ? asset($meeting->agm_file_url) : ''),
                            'egm' => ($meeting->egm ? true : false),
                            'egm_file_url' => ($meeting->egm_file_url ? asset($meeting->egm_file_url) : ''),
                            'minit_meeting' => ($meeting->minit_meeting ? true : false),
                            'minutes_meeting_file_url' => ($meeting->minutes_meeting_file_url ? asset($meeting->minutes_meeting_file_url) : ''),
                            'jmc_spa' => ($meeting->jmc_spa ? true : false),
                            'jmc_file_url' => ($meeting->jmc_file_url ? asset($meeting->jmc_file_url) : ''),
                            'identity_card' => ($meeting->identity_card ? true : false),
                            'ic_file_url' => ($meeting->ic_file_url ? asset($meeting->ic_file_url) : ''),
                            'attendance' => ($meeting->attendance ? true : false),
                            'attendance_file_url' => ($meeting->attendance_file_url ? asset($meeting->attendance_file_url) : ''),
                            'financial_report' => ($meeting->financial_report ? true : false),
                            'audited_financial_file_url' => ($meeting->audited_financial_file_url ? asset($meeting->audited_financial_file_url) : ''),
                            'audit_report' => ($meeting->audit_report ? $meeting->audit_report : ''),
                            'audit_report_url' => ($meeting->audit_report_url ? asset($meeting->audit_report_url) : ''),
                            'letter_integrity_url' => ($meeting->letter_integrity_url ? asset($meeting->letter_integrity_url) : ''),
                            'letter_bankruptcy_url' => ($meeting->letter_bankruptcy_url ? asset($meeting->letter_bankruptcy_url) : ''),
                            'notice_agm_egm_url' => ($meeting->notice_agm_egm_url ? asset($meeting->notice_agm_egm_url) : ''),
                            'minutes_agm_egm_url' => ($meeting->minutes_agm_egm_url ? asset($meeting->minutes_agm_egm_url) : ''),
                            'minutes_ajk_url' => ($meeting->minutes_ajk_url ? asset($meeting->minutes_ajk_url) : ''),
                            'eligible_vote_url' => ($meeting->eligible_vote_url ? asset($meeting->eligible_vote_url) : ''),
                            'attend_meeting_url' => ($meeting->attend_meeting_url ? asset($meeting->attend_meeting_url) : ''),
                            'proksi_url' => ($meeting->proksi_url ? asset($meeting->proksi_url) : ''),
                            'ajk_info_url' => ($meeting->ajk_info_url ? asset($meeting->ajk_info_url) : ''),
                            'ic_url' => ($meeting->ic_url ? asset($meeting->ic_url) : ''),
                            'purchase_aggrement_url' => ($meeting->purchase_aggrement_url ? asset($meeting->purchase_aggrement_url) : ''),
                            'strata_title_url' => ($meeting->strata_title_url ? asset($meeting->strata_title_url) : ''),
                            'maintenance_statement_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                            'integrity_pledge_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                            'report_audited_financial_url' => ($meeting->report_audited_financial_url ? asset($meeting->report_audited_financial_url) : ''),
                            'house_rules_url' => ($meeting->house_rules_url ? asset($meeting->house_rules_url) : ''),
                            'audit_start_date' => ($meeting->audit_start_date ? $meeting->audit_start_date : ''),
                            'audit_end_date' => ($meeting->audit_end_date ? $meeting->audit_end_date : ''),
                            'remarks' => ($meeting->remarks ? $meeting->remarks : ''),
                            'created_at' => ($meeting->created_at ? $meeting->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($meeting->updated_at ? $meeting->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_meetings,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_meetings / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_meetings ? ($current_page * $per_page) : $total_meetings,
                        'data' => $meetingList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function meetingMC() {
        $result = array();
        $meetingList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_meetings = MeetingDocument::where('file_id', $file->id)
                        ->where('type', 'mc')
                        ->where('is_deleted', 0)
                        ->count();

                $meetings = MeetingDocument::where('file_id', $file->id)
                        ->where('type', 'mc')
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($meetings) {
                    foreach ($meetings as $meeting) {
                        $meetingList[] = array(
                            'id' => $meeting->id,
                            'agm_date' => ($meeting->agm_date ? $meeting->agm_date : ''),
                            'agm' => ($meeting->agm ? true : false),
                            'agm_file_url' => ($meeting->agm_file_url ? asset($meeting->agm_file_url) : ''),
                            'egm' => ($meeting->egm ? true : false),
                            'egm_file_url' => ($meeting->egm_file_url ? asset($meeting->egm_file_url) : ''),
                            'minit_meeting' => ($meeting->minit_meeting ? true : false),
                            'minutes_meeting_file_url' => ($meeting->minutes_meeting_file_url ? asset($meeting->minutes_meeting_file_url) : ''),
                            'jmc_spa' => ($meeting->jmc_spa ? true : false),
                            'jmc_file_url' => ($meeting->jmc_file_url ? asset($meeting->jmc_file_url) : ''),
                            'identity_card' => ($meeting->identity_card ? true : false),
                            'ic_file_url' => ($meeting->ic_file_url ? asset($meeting->ic_file_url) : ''),
                            'attendance' => ($meeting->attendance ? true : false),
                            'attendance_file_url' => ($meeting->attendance_file_url ? asset($meeting->attendance_file_url) : ''),
                            'financial_report' => ($meeting->financial_report ? true : false),
                            'audited_financial_file_url' => ($meeting->audited_financial_file_url ? asset($meeting->audited_financial_file_url) : ''),
                            'audit_report' => ($meeting->audit_report ? $meeting->audit_report : ''),
                            'audit_report_url' => ($meeting->audit_report_url ? asset($meeting->audit_report_url) : ''),
                            'letter_integrity_url' => ($meeting->letter_integrity_url ? asset($meeting->letter_integrity_url) : ''),
                            'letter_bankruptcy_url' => ($meeting->letter_bankruptcy_url ? asset($meeting->letter_bankruptcy_url) : ''),
                            'notice_agm_egm_url' => ($meeting->notice_agm_egm_url ? asset($meeting->notice_agm_egm_url) : ''),
                            'minutes_agm_egm_url' => ($meeting->minutes_agm_egm_url ? asset($meeting->minutes_agm_egm_url) : ''),
                            'minutes_ajk_url' => ($meeting->minutes_ajk_url ? asset($meeting->minutes_ajk_url) : ''),
                            'eligible_vote_url' => ($meeting->eligible_vote_url ? asset($meeting->eligible_vote_url) : ''),
                            'attend_meeting_url' => ($meeting->attend_meeting_url ? asset($meeting->attend_meeting_url) : ''),
                            'proksi_url' => ($meeting->proksi_url ? asset($meeting->proksi_url) : ''),
                            'ajk_info_url' => ($meeting->ajk_info_url ? asset($meeting->ajk_info_url) : ''),
                            'ic_url' => ($meeting->ic_url ? asset($meeting->ic_url) : ''),
                            'purchase_aggrement_url' => ($meeting->purchase_aggrement_url ? asset($meeting->purchase_aggrement_url) : ''),
                            'strata_title_url' => ($meeting->strata_title_url ? asset($meeting->strata_title_url) : ''),
                            'maintenance_statement_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                            'integrity_pledge_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                            'report_audited_financial_url' => ($meeting->report_audited_financial_url ? asset($meeting->report_audited_financial_url) : ''),
                            'house_rules_url' => ($meeting->house_rules_url ? asset($meeting->house_rules_url) : ''),
                            'audit_start_date' => ($meeting->audit_start_date ? $meeting->audit_start_date : ''),
                            'audit_end_date' => ($meeting->audit_end_date ? $meeting->audit_end_date : ''),
                            'remarks' => ($meeting->remarks ? $meeting->remarks : ''),
                            'created_at' => ($meeting->created_at ? $meeting->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($meeting->updated_at ? $meeting->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_meetings,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_meetings / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_meetings ? ($current_page * $per_page) : $total_meetings,
                        'data' => $meetingList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function designation() {
        $result = array();
        $designationList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_designations = AJKDetails::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->count();

                $designations = AJKDetails::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($designations) {
                    foreach ($designations as $designation) {
                        $designationList[] = array(
                            'id' => $designation->id,
                            'designation_id' => $designation->designation,
                            'designation' => ($designation->designation ? $designation->designations->description : ''),
                            'name' => ($designation->name ? $designation->name : ''),
                            'phone_no' => ($designation->phone_no ? $designation->phone_no : ''),
                            'month' => ($designation->month ? $designation->month : ''),
                            'year' => ($designation->year ? $designation->year : ''),
                            'remarks' => ($designation->remarks ? $designation->remarks : ''),
                            'created_at' => ($designation->created_at ? $designation->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($designation->updated_at ? $designation->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_designations,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_designations / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_designations ? ($current_page * $per_page) : $total_designations,
                        'data' => $designationList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function other() {
        $result = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                if ($file->other) {
                    $result[] = array(
                        'id' => $file->other->id,
                        'name' => ($file->other->name ? $file->other->name : ''),
                        'image_url' => ($file->other->image_url ? asset($file->other->image_url) : ''),
                        'latitude' => ($file->other->latitude != '0.0000000' ? $file->other->latitude : ''),
                        'longitude' => ($file->other->longitude != '0.0000000' ? $file->other->longitude : ''),
                        'description' => ($file->other->description ? $file->other->description : ''),
                        'pms_system' => ($file->other->pms_system ? $file->other->pms_system : ''),
                        'owner_occupied' => ($file->other->owner_occupied ? true : false),
                        'rented' => ($file->other->rented ? true : false),
                        'bantuan_lphs' => ($file->other->bantuan_lphs ? true : false),
                        'bantuan_others' => ($file->other->bantuan_others ? true : false),
                        'rsku' => ($file->other->rsku ? $file->other->rsku : ''),
                        'water_meter' => ($file->other->water_meter ? $file->other->water_meter : ''),
                        'malay_composition' => ($file->other->malay_composition ? $file->other->malay_composition : ''),
                        'chinese_composition' => ($file->other->malay_composition ? $file->other->malay_composition : ''),
                        'indian_composition' => ($file->other->indian_composition ? $file->other->indian_composition : ''),
                        'others_composition' => ($file->other->others_composition ? $file->other->others_composition : ''),
                        'foreigner_composition' => ($file->other->foreigner_composition ? $file->other->foreigner_composition : ''),
                        'house_scheme' => ($file->other->house_scheme ? $file->other->house_scheme : ''),
                        'created_at' => ($file->other->created_at ? $file->other->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->other->updated_at ? $file->other->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function rating() {
        $result = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $rating = Scoring::where('file_id', $file->id)->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
                if ($rating) {
                    foreach ($rating as $ratings) {
                        $ratings_A = ((($ratings->score1 + $ratings->score2 + $ratings->score3 + $ratings->score4 + $ratings->score5) / 20) * 25);
                        $ratings_B = ((($ratings->score6 + $ratings->score7 + $ratings->score8 + $ratings->score9 + $ratings->score10) / 20) * 25);
                        $ratings_C = ((($ratings->score11 + $ratings->score12 + $ratings->score13 + $ratings->score14) / 16) * 20);
                        $ratings_D = ((($ratings->score15 + $ratings->score16 + $ratings->score17 + $ratings->score18) / 16) * 20);
                        $ratings_E = ((($ratings->score19 + $ratings->score20 + $ratings->score21) / 12) * 10);

                        $rating = 0;
                        if ($ratings->total_score >= 81) {
                            $rating = 5;
                        } else if ($ratings->total_score >= 61) {
                            $rating = 4;
                        } else if ($ratings->total_score >= 41) {
                            $rating = 3;
                        } else if ($ratings->total_score >= 21) {
                            $rating = 2;
                        } else if ($ratings->total_score >= 1) {
                            $rating = 1;
                        }

                        $result[] = array(
                            'id' => $ratings->id,
                            'date' => ((!empty($ratings->date) && $ratings->date != '0000-00-00') ? date('d-M-Y', strtotime($ratings->date)) : ''),
                            'rating_A' => number_format($ratings_A, 2),
                            'rating_B' => number_format($ratings_B, 2),
                            'rating_C' => number_format($ratings_C, 2),
                            'rating_D' => number_format($ratings_D, 2),
                            'rating_E' => number_format($ratings_E, 2),
                            'total_score' => number_format($ratings->total_score, 2),
                            'rating' => $rating,
                        );
                    }
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function addRating() {
        $result = array();

        $file_id = Request::get('file_id');
        $survey = 'strata_management';
        $date = Request::get('date');
        $score1 = Request::get('score1');
        $score2 = Request::get('score2');
        $score3 = Request::get('score3');
        $score4 = Request::get('score4');
        $score5 = Request::get('score5');
        $score6 = Request::get('score6');
        $score7 = Request::get('score7');
        $score8 = Request::get('score8');
        $score9 = Request::get('score9');
        $score10 = Request::get('score10');
        $score11 = Request::get('score11');
        $score12 = Request::get('score12');
        $score13 = Request::get('score13');
        $score14 = Request::get('score14');
        $score15 = Request::get('score15');
        $score16 = Request::get('score16');
        $score17 = Request::get('score17');
        $score18 = Request::get('score18');
        $score19 = Request::get('score19');
        $score20 = Request::get('score20');
        $score21 = Request::get('score21');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
                $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
                $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
                $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
                $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

                $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

                $scoring = new Scoring();
                $scoring->file_id = $file->id;
                $scoring->survey = $survey;
                $scoring->date = $date;
                $scoring->score1 = $score1;
                $scoring->score2 = $score2;
                $scoring->score3 = $score3;
                $scoring->score4 = $score4;
                $scoring->score5 = $score5;
                $scoring->score6 = $score6;
                $scoring->score7 = $score7;
                $scoring->score8 = $score8;
                $scoring->score9 = $score9;
                $scoring->score10 = $score10;
                $scoring->score11 = $score11;
                $scoring->score12 = $score12;
                $scoring->score13 = $score13;
                $scoring->score14 = $score14;
                $scoring->score15 = $score15;
                $scoring->score16 = $score16;
                $scoring->score17 = $score17;
                $scoring->score18 = $score18;
                $scoring->score19 = $score19;
                $scoring->score20 = $score20;
                $scoring->score21 = $score21;
                $scoring->total_score = $total_score;
                $success = $scoring->save();

                if ($success) {
                    // Audit Trail
                    $remarks = 'COB Rating (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been added.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = $user->id;
                    $auditTrail->save();

                    $result[] = array(
                        'id' => $scoring->id,
                        'date' => ((!empty($scoring->date) && $scoring->date != '0000-00-00') ? date('d-M-Y', strtotime($scoring->date)) : ''),
                        'rating_A' => number_format($ratings_A, 2),
                        'rating_B' => number_format($ratings_B, 2),
                        'rating_C' => number_format($ratings_C, 2),
                        'rating_D' => number_format($ratings_D, 2),
                        'rating_E' => number_format($ratings_E, 2),
                        'total_score' => number_format($scoring->total_score, 2)
                    );

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function editRating() {
        $result = array();

        $id = Request::get('id');
        $date = Request::get('date');
        $score1 = Request::get('score1');
        $score2 = Request::get('score2');
        $score3 = Request::get('score3');
        $score4 = Request::get('score4');
        $score5 = Request::get('score5');
        $score6 = Request::get('score6');
        $score7 = Request::get('score7');
        $score8 = Request::get('score8');
        $score9 = Request::get('score9');
        $score10 = Request::get('score10');
        $score11 = Request::get('score11');
        $score12 = Request::get('score12');
        $score13 = Request::get('score13');
        $score14 = Request::get('score14');
        $score15 = Request::get('score15');
        $score16 = Request::get('score16');
        $score17 = Request::get('score17');
        $score18 = Request::get('score18');
        $score19 = Request::get('score19');
        $score20 = Request::get('score20');
        $score21 = Request::get('score21');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
            $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
            $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
            $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
            $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

            $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

            $scoring = Scoring::find($id);
            if ($scoring) {
                $scoring->date = $date;
                $scoring->score1 = $score1;
                $scoring->score2 = $score2;
                $scoring->score3 = $score3;
                $scoring->score4 = $score4;
                $scoring->score5 = $score5;
                $scoring->score6 = $score6;
                $scoring->score7 = $score7;
                $scoring->score8 = $score8;
                $scoring->score9 = $score9;
                $scoring->score10 = $score10;
                $scoring->score11 = $score11;
                $scoring->score12 = $score12;
                $scoring->score13 = $score13;
                $scoring->score14 = $score14;
                $scoring->score15 = $score15;
                $scoring->score16 = $score16;
                $scoring->score17 = $score17;
                $scoring->score18 = $score18;
                $scoring->score19 = $score19;
                $scoring->score20 = $score20;
                $scoring->score21 = $score21;
                $scoring->total_score = $total_score;
                $success = $scoring->save();

                if ($success) {
                    // Audit Trail
                    $remarks = 'COB Rating (' . $scoring->file->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = $user->id;
                    $auditTrail->save();

                    $result[] = array(
                        'id' => $scoring->id,
                        'date' => ((!empty($scoring->date) && $scoring->date != '0000-00-00') ? date('d-M-Y', strtotime($scoring->date)) : ''),
                        'rating_A' => number_format($ratings_A, 2),
                        'rating_B' => number_format($ratings_B, 2),
                        'rating_C' => number_format($ratings_C, 2),
                        'rating_D' => number_format($ratings_D, 2),
                        'rating_E' => number_format($ratings_E, 2),
                        'total_score' => number_format($scoring->total_score, 2)
                    );

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function buyer() {
        $result = array();
        $buyerList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_buyers = Buyer::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->count();

                $buyers = Buyer::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->orderBy('unit_no')
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($buyers) {
                    foreach ($buyers as $buyer) {
                        $buyerList[] = array(
                            'id' => $buyer->id,
                            'unit_no' => ($buyer->unit_no ? $buyer->unit_no : ''),
                            'unit_share' => ($buyer->unit_share ? $buyer->unit_share : ''),
                            'owner_name' => ($buyer->owner_name ? $buyer->owner_name : ''),
                            'ic_company_no' => ($buyer->ic_company_no ? $buyer->ic_company_no : ''),
                            'address' => ($buyer->address ? $buyer->address : ''),
                            'phone_no' => ($buyer->phone_no ? $buyer->phone_no : ''),
                            'email' => ($buyer->email ? $buyer->email : ''),
                            'race_id' => $buyer->race_id,
                            'race' => ($buyer->race_id ? $buyer->race->name_en : ''),
                            'nationality_id' => $buyer->nationality_id,
                            'nationality' => ($buyer->nationality_id ? $buyer->nationality->name : ''),
                            'no_petak' => ($buyer->no_petak ? $buyer->no_petak : ''),
                            'no_petak_aksesori' => ($buyer->no_petak_aksesori ? $buyer->no_petak_aksesori : ''),
                            'keluasan_lantai_petak' => ($buyer->keluasan_lantai_petak ? $buyer->keluasan_lantai_petak : ''),
                            'keluasan_lantai_petak_aksesori' => ($buyer->keluasan_lantai_petak_aksesori ? $buyer->keluasan_lantai_petak_aksesori : ''),
                            'jenis_kegunaan' => ($buyer->jenis_kegunaan ? $buyer->jenis_kegunaan : ''),
                            'nama2' => ($buyer->nama2 ? $buyer->nama2 : ''),
                            'ic_no2' => ($buyer->ic_no2 ? $buyer->ic_no2 : ''),
                            'alamat_surat_menyurat' => ($buyer->alamat_surat_menyurat ? $buyer->alamat_surat_menyurat : ''),
                            'caj_penyelenggaraan' => ($buyer->caj_penyelenggaraan ? $buyer->caj_penyelenggaraan : ''),
                            'sinking_fund' => ($buyer->sinking_fund ? $buyer->sinking_fund : ''),
                            'remarks' => ($buyer->remarks ? $buyer->remarks : ''),
                            'created_at' => ($buyer->created_at ? $buyer->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($buyer->updated_at ? $buyer->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_buyers,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_buyers / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_buyers ? ($current_page * $per_page) : $total_buyers,
                        'data' => $buyerList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function document() {
        $result = array();
        $documentsList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_documents = Document::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->count();

                $documents = Document::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($documents) {
                    foreach ($documents as $document) {
                        $documentsList[] = array(
                            'id' => $document->id,
                            'document_type_id' => $document->document_type_id,
                            'document_type' => ($document->document_type_id ? $document->type->name : ''),
                            'name' => ($document->name ? $document->name : ''),
                            'remarks' => ($document->remarks ? $document->remarks : ''),
                            'file_url' => ($document->file_url ? asset($document->file_url) : ''),
                            'is_hidden' => ($document->is_hidden ? true : false),
                            'is_readonly' => ($document->is_readonly ? true : false),
                            'created_at' => ($document->created_at ? $document->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($document->updated_at ? $document->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_documents,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_documents / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_documents ? ($current_page * $per_page) : $total_documents,
                        'data' => $documentsList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function insurance() {
        $result = array();
        $insurancesList = array();

        $file_id = Request::get('file_id');

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $current_page = (Request::has('page')) ? Request::get('page') : 1;
            $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
            $from = ($current_page - 1) * $per_page;

            $file = Files::find($file_id);
            if ($file) {
                $total_insurances = Insurance::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->count();

                $insurances = Insurance::where('file_id', $file->id)
                        ->where('is_deleted', 0)
                        ->skip($from)
                        ->take($per_page)
                        ->get();

                if ($insurances) {
                    foreach ($insurances as $insurance) {
                        $insurancesList[] = array(
                            'id' => $insurance->id,
                            'insurance_provider_id' => $insurance->insurance_provider_id,
                            'insurance_provider' => ($insurance->insurance_provider_id ? $insurance->provider->name : ''),
                            'remarks' => ($insurance->remarks ? $insurance->remarks : ''),
                            'created_at' => ($insurance->created_at ? $insurance->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($insurance->updated_at ? $insurance->updated_at->format('Y-m-d H:i:s') : '')
                        );
                    }

                    $result[] = array(
                        'total' => $total_insurances,
                        'per_page' => $per_page,
                        'page' => ceil($current_page),
                        'last_page' => ceil($total_insurances / $per_page),
                        'from' => $from + 1,
                        'to' => ($current_page * $per_page) < $total_insurances ? ($current_page * $per_page) : $total_insurances,
                        'data' => $insurancesList
                    );
                }

                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            } else {
                $response = array(
                    'error' => true,
                    'message' => 'Invalid File',
                    'result' => false,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function search() {
        $result = array();
        $fileList = array();

        $total_files = 0;
        $keyword = Request::get('keyword');
        $current_page = ((Request::has('page') && !empty(Request::get('page'))) ? Request::get('page') : 1);
        $per_page = ((Request::has('per_page') && !empty(Request::get('per_page'))) ? Request::get('per_page') : 15);
        $from = ($current_page - 1) * $per_page;
        $order_raw = ((Request::has('order') && !empty(Request::has('order'))) ? Request::get('order') : 'file_name');
        $dir = ((Request::has('dir') && !empty(Request::has('dir'))) ? Request::get('dir') : 'asc');

        if ($order_raw && $order_raw == 'file_no') {
            $order = 'files.file_no';
        } else if ($order_raw && $order_raw == 'file_name') {
            $order = 'strata.name';
        } else if ($order_raw && $order_raw == 'company') {
            $order = 'company.short_name';
        } else if ($order_raw && $order_raw == 'year') {
            $order = 'strata.year';
        } else {
            $order = 'strata.name';
        }

        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            if (!$user->getAdmin()) {
                if (!empty($user->file_id)) {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.id', $user->file_id)
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.id', $user->file_id)
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                } else {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.company_id', $user->company_id)
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                } else {
                    $total_files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->count();

                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*', 'strata.id as strata_id'])
                            ->where(function($query) use ($keyword) {
                                $query->where('files.file_no', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('company.short_name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.name', 'LIKE', "%" . $keyword . "%")
                                ->orWhere('strata.year', 'LIKE', "%" . $keyword . "%");
                            })
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('files.is_active', '!=', 2)
                            ->where('files.is_deleted', 0)
                            ->skip($from)
                            ->take($per_page)
                            ->orderBy($order, $dir)
                            ->get();
                }
            }

            if ($files) {
                foreach ($files as $file) {
                    $fileList[] = array(
                        'id' => $file->id,
                        'company_id' => $file->company_id,
                        'company' => ($file->company_id ? $file->company->short_name : ''),
                        'file_no' => ($file->file_no ? $file->file_no : ''),
                        'file_name' => ($file->strata_id ? $file->strata->name : ''),
                        'year' => (($file->strata_id && $file->strata->year > 0) ? $file->strata->year : ''),
                        'remarks' => ($file->remarks ? $file->remarks : ''),
                        'created_at' => ($file->created_at ? $file->created_at->format('Y-m-d H:i:s') : ''),
                        'updated_at' => ($file->updated_at ? $file->updated_at->format('Y-m-d H:i:s') : '')
                    );
                }
            }

            $result[] = array(
                'keyword' => $keyword,
                'oder' => $order_raw,
                'dir' => $dir,
                'page' => ceil($current_page),
                'total' => $total_files,
                'per_page' => $per_page,
                'last_page' => ceil($total_files / $per_page),
                'from' => $from + 1,
                'to' => ($current_page * $per_page) < $total_files ? ($current_page * $per_page) : $total_files,
                'data' => $fileList
            );

            $response = array(
                'error' => false,
                'message' => 'Success',
                'result' => $result,
            );

            return Response::json($response);
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

}
