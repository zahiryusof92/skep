<?php

namespace Helper;

// use Exception;
use Illuminate\Support\Facades\Config;

use Helper\KCurl;

class OAuth
{

    public function getHeaders() {
        $headers =  [
            "Content-Type: application/json",
            "Accept: application/json",
            // 'Authorization' => "Bearer ". config('constant.notification.auth_token'),
        ];

        return $headers;
    }
    
    public function updateSimpleProfile($client_data) {
        
        // try {
            $data = [
                'username'   => $client_data['username'],
                'password'   => $client_data['password'],
                'full_name'   => $client_data['full_name'],
                'email'   => $client_data['email'],
                'phone_no'   => $client_data['phone_no'],
            ];

            $response['content'] = (string) ((new KCurl())->requestPost($this->getHeaders(), 
                                    Config::get('constant.module.auth.sso.update_profile_url'),
                                    json_encode($data)));
                                    
        // } catch(Exception $e) {

        //     throw($e);
        
        // }

        return $response;
    }
}