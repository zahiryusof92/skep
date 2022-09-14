<?php

namespace Helper;

use Files;
use Illuminate\Support\Facades\Config;
use User;

class OdesiLife
{
    public function __construct()
    {
        $this->api_domain = Config::get('constant.third_party.life.api_domain');
        $this->api_token = Config::get('constant.third_party.life.api_token');
    }

    public function login($data)
    {
        $url = $this->api_domain . 'ecob/login';
        $response = json_decode($this->curlPOST($url, $data));

        return $response;
    }

    public function owners($data)
    {
        $url = $this->api_domain . 'ecob/users';
        $response = json_decode($this->curlPOST($url, $data));

        return $response;
    }

    private function curlGET($url)
    {
        $header = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->api_token,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    private function curlPOST($url, $data)
    {
        $header = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->api_token,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
