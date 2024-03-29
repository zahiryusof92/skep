<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController {

    public function token() {
        try {

            $data = \Input::all();

            $validation_rules = [
                'username' => 'required',
                'password' => 'required',
            ];

            $validator = \Validator::make($data, $validation_rules, []);

            if ($validator->fails()) {

                $errors = $validator->errors();

                $response = [
                    'data' => $errors,
                    'message' => 'Validation Error'
                ];
                return Response::json($response, 422);
            }

            $auth = Auth::attempt(array(
                        'username' => $data['username'],
                        'password' => $data['password'],
                        'status' => 1,
                        'is_active' => 1,
                        'is_deleted' => 0,
                            ), false);
                            
            if($auth) {
                $user = User::find(Auth::user()->id);
                $response = [
                    'id' => $user->id,
                    'token' => JWTAuth::fromUser($user),
                    'message' => 'Login Success'
                ];
                return Response::json($response);
            } else {
                $response = [
                    'error' => true,
                    'message' => 'Login Fail'
                ];
                return Response::json($response, 401);
            }
        } catch (Exception $e) {
            throw($e);
        }

    }
}