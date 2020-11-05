<?php

namespace Api;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use User;

class LoginController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $username = Request::get('username');
        $password = Request::get('password');
        $remember = (Request::has('remember')) ? true : false;

        $auth = Auth::attempt(array(
                    'username' => $username,
                    'password' => $password,
                    'status' => 1,
                    'is_active' => 1,
                    'is_deleted' => 0,
                        ), $remember);

        if ($auth) {
            $user = User::find(Auth::user()->id);

            if ($user) {
                $result = array(
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

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
