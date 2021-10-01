<?php

use Helper\KCurl;
use Helper\OAuth;

class UserController extends BaseController {

    public function changeLanguage($lang) {
        Session::forget('lang');
        Session::put('lang', $lang);

        return Redirect::back();
    }

    public function changeCOB($id) {
        Session::forget('admin_cob');

        $cob = Company::find($id);
        if ($cob) {
            if ($cob->id != 1) {
                Session::put('admin_cob', $id);
            }
        }

        return Redirect::back();
    }

    //register
    public function register() {
        $company = Company::where('is_main', 0)->where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.forms.register'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'cob' => $company
        );

        return View::make('user_en.register', $viewData);
    }

    public function submitRegister() {
        $data = Input::all();
        if (Request::ajax()) {

            $username = $data['username'];
            $password = $data['password'];
            $name = $data['name'];
            $email = $data['email'];
            $phone_no = $data['phone_no'];
            $company = $data['company'];

            $check_username = User::where('username', $username)->where('is_deleted', 0)->count();

            if ($check_username <= 0) {
                $user = new User();
                $user->username = $username;
                $user->password = Hash::make($password);
                $user->full_name = $name;
                $user->email = $email;
                $user->phone_no = $phone_no;
                $user->remarks = "";
                $user->role = 2;
                $user->status = 0;
                $user->company_id = $company;
                $user->is_active = 0;
                $user->is_deleted = 0;
                $success = $user->save();

                if ($success) {
                    # Audit Trail
                    $remarks = 'User ' . $user->username . ' has been registered.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = $user->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "username_in_use";
            }
        }
    }

    //member login start
    public function login($cob = '') {
        if (empty($cob)) {
            $host = '';
            if (!empty($_SERVER['HTTP_HOST'])) {
                $host = $_SERVER['HTTP_HOST'];
            }
            $actual_link = "$host$_SERVER[REQUEST_URI]";

            if (strpos($actual_link, 'mbs') !== false) {
                $cob = 'mbs';
            } else if (strpos($actual_link, 'mps') !== false) {
                $cob = 'mps';
            }
        }

        if (!empty($cob)) {
            $company = Company::where('short_name', $cob)->where('is_deleted', 0)->first();

            if ($company) {
                $viewData = array(
                    'title' => trans('app.forms.login'),
                    'panel_nav_active' => '',
                    'main_nav_active' => '',
                    'sub_nav_active' => '',
                    'cob' => $cob
                );

                return View::make('user_en.login', $viewData);
            } else {
                $viewData = array(
                    'title' => trans('app.errors.page_not_found')
                );

                return View::make('404_en', $viewData);
            }
        } else {
            $viewData = array(
                'title' => trans('app.forms.login'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'cob' => $cob
            );

            return View::make('user_en.login', $viewData);
        }
    }

    public function loginAction() {

        $form = Input::all();

        $validator = Validator::make($form, array(
                    'username' => 'required',
                    'password' => 'required'
        ));

        $cob = Input::get('cob');
        
        if (isset($cob) && !empty($cob)) {
            $cob_company = Company::where('short_name', $cob)->where('is_main', 0)->first();
            
            if ($cob_company) {
                if ($validator->fails()) {
                    return Redirect::to('/' . $cob)->withErrors($validator)->withInput();
                } else {
                    $remember = (Input::has('remember')) ? true : false;
                    $username = Input::get('username');
                    $password = Input::get('password');

                    $auth = Auth::attempt(array(
                                'username' => $username,
                                'password' => $password,
                                'company_id' => $cob_company->id,
                                'status' => 1,
                                'is_active' => 1,
                                'is_deleted' => 0,
                                    ), $remember);
                                    
                    if ($auth) {
                        ## EAI Call
                        // $url = $this->eai_domain . $this->eai_route['auth']['login'];
                        // $api_data['username'] = $username;
                        // $api_data['password'] = $password;
                        // $headers =  [
                        //     "Content-Type: application/json",
                        //     "Accept: application/json",
                        // ];
                        // $response = json_decode((string) ((new KCurl())->requestPost($headers, 
                        //                         $url,
                        //                         json_encode($api_data))));
                        // if(empty($response->status) == false && $response->status == 200) {
                        //     setcookie("eai_session", $response->token, time() + (86400 *24*7));
                        // }

                        $user_account = User::where('id', Auth::user()->id)->first();
                        if ($user_account) {
                            if ($user_account->getRole->name == 'JMB' || $user_account->getRole->name == 'MC') {
                                $current = strtotime(date('Y-m-d'));
                                $start = strtotime($user_account->start_date);
                                $end = strtotime($user_account->end_date);

                                if ($current >= $start && $current <= $end) {
                                    Session::put('file_id', $user_account['file_id']);
                                } else {
                                    Auth::logout();
                                    return Redirect::to('/' . $cob)->with('login_error', trans('app.errors.account_expired'));
                                }
                            }

                            Session::put('id', $user_account['id']);
                            Session::put('username', $user_account['username']);
                            Session::put('full_name', $user_account['full_name']);
                            Session::put('role', $user_account['role']);

                            # Audit Trail
                            $remarks = 'User ' . Auth::user()->username . ' is signed.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "System Administration";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();

                            return Redirect::to('/home');
                        } else {
                            Auth::logout();
                            return Redirect::to('/' . $cob)->with('login_error', trans('app.errors.wrong_username_password'));
                        }
                    } else {
                        return Redirect::to('/' . $cob)->with('login_error', trans('app.errors.wrong_username_password'));
                    }
                }
            } else {
                return Redirect::to('/' . $cob)->with('login_error', trans('app.errors.wrong_username_password'));
            }
        } else {
            if ($validator->fails()) {
                return Redirect::to('/login')->withErrors($validator)->withInput();
            } else {
                $remember = (Input::has('remember')) ? true : false;
                $username = Input::get('username');
                $password = Input::get('password');

                $auth = Auth::attempt(array(
                            'username' => $username,
                            'password' => $password,
                            'status' => 1,
                            'is_active' => 1,
                            'is_deleted' => 0,
                                ), $remember);
                                     
                if ($auth) {
                    ## EAI Call
                    // $url = $this->eai_domain . $this->eai_route['auth']['login'];
                    // $api_data['username'] = $username;
                    // $api_data['password'] = $password;
                    // $headers =  [
                    //     "Content-Type: application/json",
                    //     "Accept: application/json",
                    // ];
                    // $response = json_decode((string) ((new KCurl())->requestPost($headers, 
                    //                         $url,
                    //                         json_encode($api_data))));
                    // if(empty($response->status) == false && $response->status == 200) {
                    //     setcookie("eai_session", $response->token, time() + (86400 *24*7));
                    // }
                    
                    if (Auth::user()->isHR() || Auth::user()->getAdmin() || Auth::user()->isLawyer()) {
                        $user_account = User::where('id', Auth::user()->id)->first();
                        if ($user_account) {
                            Session::put('id', $user_account['id']);
                            Session::put('username', $user_account['username']);
                            Session::put('full_name', $user_account['full_name']);
                            Session::put('role', $user_account['role']);

                            # Audit Trail
                            $remarks = 'User ' . Auth::user()->username . ' is signed.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "System Administration";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();
                            
                            if($user_account->isHR()) {
                                return Redirect::to('/summon/councilSummonList');
                            } else {
                                return Redirect::to('/home');
                            }
                        } else {
                            Auth::logout();
                            return Redirect::to('/login')->with('login_error', trans('app.errors.wrong_username_password'));
                        }
                    } else {
                        $user_account = User::where('id', Auth::user()->id)->first();
                        if ($user_account) {
                            Session::put('id', $user_account['id']);
                            Session::put('username', $user_account['username']);
                            Session::put('full_name', $user_account['full_name']);
                            Session::put('role', $user_account['role']);

                            # Audit Trail
                            $remarks = 'User ' . Auth::user()->username . ' is signed.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "System Administration";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();
                            
                            return Redirect::to('/home');
                        } else {
                            Auth::logout();
                            return Redirect::to('/login')->with('login_error', trans('app.errors.wrong_username_password'));
                        }
                    }
                } else {
                    return Redirect::to('/login')->with('login_error', trans('app.errors.wrong_username_password'));
                }
            }
        }
    }

    //member login end
    //edit profile
    public function editProfile() {

        $user = User::find(Auth::User()->id);
        $role = Role::find($user->role);
        $company = Company::find($user->company_id);

        $viewData = array(
            'title' => trans('app.menus.edit_profile'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'user' => $user,
            'role' => $role,
            'company' => $company,
            'image' => ""
        );
        return View::make('user_en.edit_profile', $viewData);
    }

    public function submitEditProfile() {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['profile']['update'];
            $data['id'] = Auth::User()->id;
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $user = User::find(Auth::User()->id);
            if (count($user) > 0) {
                $user->full_name = $data['name'];
                $user->email = $data['email'];
                $user->phone_no = $data['phone_no'];
                $success = $user->save();

                /**
                 * call back to vendor portal to update info
                 */
                (new OAuth())->updateSimpleProfile($user);

                if ($success) {

                    Session::forget('full_name');
                    Session::put('full_name', $user['full_name']);

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
            // } else {
            //     print "false";
            // }
        }
    }

    //Change password

    public function changePassword() {

        $user = User::find(Auth::User()->id);

        $viewData = array(
            'title' => trans('app.menus.change_password'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'user' => $user,
            'image' => ""
        );

        return View::make('user_en.change_password', $viewData);
    }

    public function checkPasswordProfile() {
        $data = Input::all();
        if (Request::ajax()) {
            $old_password = $data['old_password'];

            //incase user enters lowcase
            $new_old_password = strtoupper($data['old_password']);

            $user = User::find(Auth::User()->id);

            if (Hash::check($old_password, $user->getAuthPassword())) {
                print "true";
            } else if (Hash::check($new_old_password, $user->getAuthPassword())) {
                print "true";
            } else {
                print "false";
            }
        }
    }

    public function submitChangePassword() {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['profile']['password_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $new_password = $data['new_password'];

            $user = User::find(Auth::User()->id);
            $user->password = Hash::make($new_password);
            $success = $user->save();

            if ($success) {
                print "true";
            } else {
                print "false";
            }
            // } else {
            //     print "false";
            // }
        }
    }

    //member logout start
    public function logout($cob = '') {
        Session::forget('id');
        Session::forget('username');
        Session::forget('role');
        Session::forget('admin_cob');
        Session::forget('file_id');
        Auth::logout();

        if (!empty($cob) && $cob != 'odesi') {
            return Redirect::to('/' . $cob);
        }

        return Redirect::to('/login');
    }

}

?>
