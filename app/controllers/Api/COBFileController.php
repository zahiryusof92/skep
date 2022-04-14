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
                            ->join('management', 'files.id', '=', 'management.file_id')
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('files.file_no', "like", "%". $request['term'] ."%");
                                }
                                if(!empty($request['company_id']))  {
                                    $query->where('files.company_id', $request['company_id']);
                                }
                                if(!empty($request['strata'])) {
                                    $file_ids = [];
                                    if(is_array($request['strata']))  {
                                        Strata::whereIn('strata.name', $request['strata'])
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, $model->file_id);
                                                    }
                                                });
                                    } else {
                                        Strata::where('strata.name', "like", "%". $request['strata'] ."%")
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, $model->file_id);
                                                    }
                                                });
                                    }
                                    $query->whereIn('files.id', $file_ids);
                                }
                                if(!empty($request['management'])) {
                                    if(in_array('jmb', $request['management']) && in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_others', 1)
                                              ->orWhere('management.is_mc', 1)
                                              ->orWhere('management.is_jmb', 1);
                                    } else if(in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_others', 1)
                                              ->orWhere('management.is_mc', 1);
                                    } else if(in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_others', 1)
                                              ->orWhere('management.is_jmb', 1);
                                    } else if(in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_mc', 1)
                                              ->orWhere('management.is_jmb', 1);
                                    } else if(in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_others', 1);
                                    } else if(in_array('others', $request['management']) && in_array('mc', $request['management'])) {
                                        $query->where('management.is_others', 1)
                                              ->orWhere('management.is_mc', 1);
                                    } else if(in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->orWhere('management.is_mc', 1);
                                    } else if(in_array('jmb', $request['management']) && in_array('others', $request['management'])) {
                                        $query->where('management.is_jmb', 1)
                                              ->orWhere('management.is_others', 1);
                                    } else if(in_array('jmb', $request['management']) && in_array('agent', $request['management'])) {
                                        $query->where('management.is_jmb', 1)
                                              ->orWhere('management.is_agent', 1);
                                    } else if(in_array('jmb', $request['management']) && in_array('mc', $request['management'])) {
                                        $query->where('management.is_jmb', 1)
                                              ->orWhere('management.is_mc', 1);
                                    } else if(in_array('others', $request['management'])) {
                                        $query->where('management.is_others', 1)
                                              ->where('management.is_mc', 0);
                                    } else if(in_array('agent', $request['management'])) {
                                        $query->where('management.is_agent', 1)
                                              ->where('management.is_mc', 0);
                                    } else if(in_array('mc', $request['management'])) {
                                        $query->where('management.is_mc', 1);
                                    } else if(in_array('jmb', $request['management'])) {
                                        $query->where('management.is_jmb', 1)
                                              ->where('management.is_mc', 0);
                                    }
                                }
                            })
                            ->orderBy('files.file_no', 'asc')
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