<?php

class ImageController extends BaseController {

    public function logoImage() {
        $file = Input::file('image');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
        } else {
            $destinationPath = 'assets/common/img/logo';
            $filename = $file->getClientOriginalName();
            Input::file('image')->move($destinationPath, $filename);
            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
        }
    }

    public function navbarImage() {
        $file = Input::file('nav_image');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
        } else {
            $destinationPath = 'assets/common/img/logo';
            $filename = $file->getClientOriginalName();
            Input::file('nav_image')->move($destinationPath, $filename);
            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
        }
    }

    public function uploadOthersImage() {
        $file = Input::file('image');

        if (!empty($file)) {
            $input = array('image' => $file);
            $rules = array(
                'image' => 'image'
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
            } else {
                $destinationPath = 'uploads/images';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                Input::file('image')->move($destinationPath, $filename);
                return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
            }
        }
    }

}
