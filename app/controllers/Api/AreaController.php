<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Area;

class AreaController extends BaseController
{

    public function getOption()
    {
        if (Request::ajax()) {
            $request = Request::all();
            $options = [];
            $areas = Area::self()
                ->where(function ($query) use ($request) {
                    if (!empty($request['term'])) {
                        $query->where('description', "like", "%" . $request['term'] . "%");
                    }
                })
                ->get();

            foreach ($areas as $area) {
                array_push($options, ['id' => $area->id, 'text' => $area->description]);
            }
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);
        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}
