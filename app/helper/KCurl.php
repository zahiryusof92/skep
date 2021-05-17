<?php

namespace Helper;

use Exception;
use Illuminate\Support\Facades\Log;

class KCurl
{
    public function getHeaders($file) {
        $headers =  [
            "Content-Type: application/json",
            "Accept: application/json",
            'Authorization' => "Authorization: Bearer ". $_COOKIE["eai_session"],
        ];

        if($file) {
            array_shift($headers);
        }

        return $headers;
    }

    public function requestPost($header = null, $url, $data, $file = false) {
        try {
            if($header == null) {
                $header = $this->getHeaders($file);
            }
            
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
                Log::info('Revenue Error '.curl_error($ch));
                echo 'Error'.curl_error($ch);
            }
            
            return $response;
            
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function requestGET($header = null, $url, $file = false) {
        try {
            if($header == null) {
                $header = $this->getHeaders($file);
            }
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                Log::info('Revenue Error '.curl_error($ch));
                echo 'Error'.curl_error($ch);
            }
            
            return $response;
            
        } catch(Exception $e) {
            throw($e);
        }
    }
}