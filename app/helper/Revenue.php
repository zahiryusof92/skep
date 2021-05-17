<?php

namespace Helper;

use Helper\KCurl;
use Carbon\Carbon;

use Illuminate\Support\Facades\Config;


class Revenue
{

    public function __construct()
    {
        $this->config = Config::get('constant.module.payment.gateway.revenue');
    }
    
    private static $domains = [
        'auth' => 'oauth.revenuemonster.my',
        'api' => 'open.revenuemonster.my'
    ];

    // identifier for sandbox or production client_id, client_secret, private_key, store_id
    private $isSandbox = false;

    public function getHeader($auth = false) {
        $header = [
            "Content-Type: application/json"
        ];

        if($auth) {
            $client_id = $this->config['client_id'];
            $client_secret = $this->config['client_secret'];
            $base64_code = base64_encode("{$client_id}:{$client_secret}");

            array_push($header, "Authorization: Basic {$base64_code}");
        }

        return $header;
    }

    public function getOpenApiUrl($url, $usage = 'api', $version = 'v1') {
        if(strpos($url, 'signature')) {
            $uri = Revenue::$domains[$usage] . '/' . $url;
        } else {
            $uri = Revenue::$domains[$usage] . "/$version/" . $url;
        }
        
        if($this->isSandbox) {
            $uri = "sb-$uri";
        }
        return "https://$uri";
    }

    public function refreshToken() {

    }

    public function getAccessToken() {
        $url = $this->getOpenApiUrl('token', 'auth');
		$postfields = [
            'grantType' => 'client_credentials'
        ];
        $response = (new KCurl())->requestPost($this->getHeader(true), $url, json_encode($postfields));
        
        return json_decode($response);
    }

    public function generateSignature($requestMethod, $requestUrl = '', 
    $data = []) {

        $url = $this->getOpenApiUrl('tool/signature/generate');
        $fields = [
            // 'data' => $data,
            'method' => $requestMethod,
            "nonceStr" => $this->config['nonceStr'],
            "privateKey" => $this->config['private_key'],
            "requestUrl" => $requestUrl,
            "signType" => "sha256",
            "timestamp" => strval(Carbon::now()->timestamp)
        ];
        if(empty($data) == false) {
            $fields['data'] = $data;
        }
        
        $str = json_encode($fields);
        // $postfields = str_replace("[]","{}",$str);
        $postfields = $str;

        $response = (new KCurl())->requestPost($this->getHeader(), $url, $postfields);

        return json_decode($response);
    }

    public function getAPIHeader($data_signature) {
        $data_token = $this->getAccessToken();
        $timestamp = explode('timestamp=', $data_signature->data)[1];
        $header = [
            "Content-Type: application/json",
            "Authorization: Bearer {$data_token->accessToken}",
            "X-Signature: {$data_signature->signature}",
            "X-Nonce-Str: ". $this->config['nonceStr'],
            "X-Timestamp: {$timestamp}"
        ];
        
        return $header;

    }

    public function getFpxBank() {
        $url = $this->getOpenApiUrl('payment/fpx-bank', 'api', 'v3');
        $data_signature = $this->generateSignature('get', $url);
        
        $response = (new KCurl())->requestGET($this->getAPIHeader($data_signature), $url);
        return json_decode($response);

    }

    public function getStores() {
        $url = $this->getOpenApiUrl('stores', 'api', 'v3');
        $data_signature = $this->generateSignature('get', $url);
        
        $response = (new KCurl())->requestGET($this->getAPIHeader($data_signature), $url);
        return json_decode($response);

    }

    public function getStatusByOrderID($order_id) {
        $url = $this->getOpenApiUrl('payment/transaction/order', 'api', 'v3');
        $url .= "/$order_id";
        // dd($url);
        $data_signature = $this->generateSignature('get', $url);
        
        $response = (new KCurl())->requestGET($this->getAPIHeader($data_signature), $url);
        return json_decode($response);

    }

    public function paymentOnline($data) {
        $url = $this->getOpenApiUrl('payment/online', 'api', 'v3');
        $postfields = [
            'order' => [
                'title' => 'PaymentProcess',
                'detail' => "TransactionID : {$data['transaction_id']}",
                'additionalData' => 'Sales',
                'amount' => ($data['amount'] * 100),
                'currencyType' => 'MYR',
                'id' => $data['transaction_id']
            ],
            'customer' => [
                'userId' => '1234567',
                'email' => '',
                'countryCode' => '',
                'phoneNumber' => ''
            ],
            'method' => 
            [
                // "WECHATPAY_MY",
                // 'GOBIZ_MY'
            ],
            'type' => 'WEB_PAYMENT', // WEB_PAYMENT / MOBILE_PAYMENT
            'storeId' => $this->config['store_id'],
            'redirectUrl' => $data['redirect_url'],
            'notifyUrl' => $data['redirect_url'],
            'layoutVersion' => 'v3'
        ];
        $data_signature = $this->generateSignature('post', $url, $postfields);
        // $fields = str_replace("[]","{}",json_encode($postfields));
        $fields = json_encode($postfields);
        

        $response = (new KCurl())->requestPost($this->getAPIHeader($data_signature), $url, $fields);
        return json_decode($response);
        /** Response need to insert db */
    }

    public function refund($data) {
        $url = $this->getOpenApiUrl('payment/refund', 'api', 'v3');
        $postfields = [
			'transactionId' => $data['transaction_id'],
			"refund"	=> [
				"type" => "FULL",
				"currencyType" => "MYR",
				"amount"	=> ($data['amount'] * 100),
			],
			"reason"	=> "Wrong Item"
        ];
        $data_signature = $this->generateSignature('post', $url, $postfields);

        $response = (new KCurl())->requestPost($this->getAPIHeader($data_signature), $url, $postfields);

        return json_decode($response);
    }
}