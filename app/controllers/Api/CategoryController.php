<?php

namespace Api;

use BaseController;
use Category;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends BaseController {

    public function getOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $categories = Category::self()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('description', "like", "%". $request['term'] ."%");
                                }
                            })
                            ->get();

            foreach($categories as $category) {
                array_push($options, ['id' => $category->id, 'text' => $category->description]);
            }
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}