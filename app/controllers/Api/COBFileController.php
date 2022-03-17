<?php

namespace Api;

use BaseController;
use Files;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Strata;

class COBFileController extends BaseController {

    public function getOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $files = Files::file()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('files.file_no', "like", "%". $request['term'] ."%");
                                }
                                if(!empty($request['strata'])) {
                                    $file_ids = [];
                                    if(is_array($request['strata']))  {
                                        Strata::whereIn('strata.name', $request['strata'])
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, [$model->file_id]);
                                                    }
                                                });
                                    } else {
                                        Strata::where('strata.name', "like", "%". $request['strata'] ."%")
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, [$model->file_id]);
                                                    }
                                                });
                                    }
                                    $query->whereIn('id', $file_ids);
                                }
                            })
                            ->chunk(200, function($models) use(&$options)
                            {
                                foreach ($models as $model)
                                {
                                    array_push($options, ['id' => $model->id, 'text' => $model->file_no]);
                                }
                            });

            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}