<?php

namespace Helper;

use Exception;

class KCurl
{
    public function requestPost($header, $url, $data) {
        try {
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error'.curl_error($ch);
            }
            
            return $response;
            
        } catch(Exception $e) {
            throw($e);
        }
    }
}