<?php

use Helper\Helper;

class ConversionController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $conversion = Conversion::first();

        $viewData = array(
            'title' => trans('app.menus.master.conversion'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'conversion_list',
            'conversion' => $conversion,
            'image' => ""
        );

        return View::make('conversion.index', $viewData);
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
        $data = Input::all();

        $rules = array(
            'rate' => 'required|numeric',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = new Conversion();
            $model->rate = $data['rate'];
            $success = $model->save();

            if ($success) {
                /*
                 * add audit trail
                 */
                $remarks = 'Conversion has been inserted.';
                $module = 'Master Setup';
                $this->createAuditTrail($remarks, $module);

                return Redirect::to('conversion')->with('success', trans('app.successes.saved_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
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
        $data = Input::all();

        $rules = array(
            'rate' => 'required|numeric',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = Conversion::findOrFail(Helper::decode($id));
            if ($model) {
                $model->rate = $data['rate'];
                $success = $model->save();

                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Conversion has been inserted.';
                    $module = 'Master Setup';
                    $this->createAuditTrail($remarks, $module);

                    return Redirect::to('conversion')->with('success', trans('app.successes.updated_successfully'));
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
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

    public function createAuditTrail($remarks, $module) {
        # Audit Trail        
        $auditTrail = new AuditTrail();
        $auditTrail->module = $module;
        $auditTrail->remarks = $remarks;
        $auditTrail->audit_by = Auth::user()->id;
        $auditTrail->save();
    }

}
