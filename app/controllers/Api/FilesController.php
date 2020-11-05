<?php

namespace Api;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use User;
use Files;

class FilesController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $user_id = Request::get('user_id');
        $page = Request::get('page');
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

        if ($files) {
            $response = array(
                'error' => false,
                'message' => 'Success',
                'result' => $files->toArray(),
            );

            return Response::json($response);
        }

        $response = array(
            'error' => true,
            'message' => 'Error',
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
        $files = Files::find($id);

        if ($files) {
            $result = array(
                'files' => $files->toArray(),
                'strata' => $files->strata
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
            'message' => 'Error',
            'result' => false,
        );

        return Response::json($id);
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
