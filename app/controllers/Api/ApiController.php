<?php

namespace Api;

use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use User;
use Files;

class ApiController extends BaseController {

    public function login() {
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
                $result[] = array(
                    'user' => $user->toArray(),
                    'role' => $user->getRole,
                    'cob' => $user->getCOB,
                    'files' => $user->getFile
                );

                $response = array(
                    'error' => false,
                    'message' => 'Login success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Invalid user',
            'result' => false,
        );

        return Response::json($response);
    }

    public function files() {
        $user_id = Request::get('user_id');

        $user = User::find($user_id);
        if ($user) {
            $page = (Request::has('page')) ? Request::get('page') : 1;
            $page_per_page = (Request::has('page_per_page')) ? Request::get('page_per_page') : 10;

            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $files = Files::where('id', Auth::user()->file_id)
                            ->where('company_id', Auth::user()->company_id)
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                } else {
                    $files = Files::where('company_id', Auth::user()->company_id)
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $files = Files::where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                } else {
                    $files = Files::where('company_id', Session::get('admin_cob'))
                            ->where('is_active', '!=', 2)
                            ->where('is_deleted', 0)
                            ->paginate($page_per_page);
                }
            }
            
            $result = array(
                $files->toArray()
            );

            if ($files) {
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
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

    public function fileDetails() {
        $user_id = Request::get('user_id');
        $file_id = Request::get('file_id');

        $user = User::find($user_id);
        if ($user) {
            $file = Files::find($file_id);
            if ($file) {
                $result[] = array(
                    'files' => $file->toArray(),
                    'strata' => $file->strata
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
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($response);
    }

}
