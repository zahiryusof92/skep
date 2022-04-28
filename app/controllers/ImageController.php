<?php

use Helper\KCurl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['others']['image_upload'];
        if(!empty($file)) {
            $data['image'] = curl_file_create($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
            
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         $data, true)));
                                    
            // if(empty($response->status) == false && $response->status == 200) {
    
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
                        
                        # Audit Trail
                        $remarks = $filename . $this->module['audit']['text']['data_uploaded'];
                        $this->addAudit(Auth::user()->file_id, "COB Finance", $remarks);
    
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                }
            // }
        }
    }

}
