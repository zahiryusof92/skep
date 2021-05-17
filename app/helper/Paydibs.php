<?php

namespace Helper;

// use Exception;
use Illuminate\Support\Facades\Config;


class Paydibs
{

    public function generateSign($data, $type) {
        $config = Config::get('constant.module.payment.gateway');
        $string = $config['paydibs']['merchant_password'] . $type .
        $config['paydibs']['merchant_id'] . $data['payment_id'] .
        $data['order_id'] . $data['redirect_url'] . $data['amount'] .
        'MYR' . $data['customer_ip'] . $data['callback_url'];
        
        return hash('sha512', $string);
    }

    public function payRequest($data) {
        $get_data = [
            'TxnType' => 'PAY',
            'MerchantID' => Config::get('constant.module.payment.gateway.paydibs.merchant_id'),
            'MerchantPymtID' =>  $data['payment_id'],
            'MerchantOrdID' => $data['order_id'],
            'MerchantOrdDesc' => $data['description'],
            'MerchantTxnAmt' => $data['amount'],
            'MerchantCurrCode' => 'MYR',
            'MerchantRURL' => $data['redirect_url'],
            'CustIP' => $data['customer_ip'],
            'CustName' => $data['customer_name'],
            'CustEmail' => $data['customer_email'],
            'CustPhone' => $data['customer_phone'],
            'Sign' => $data['sign'],
            'MerchantCallbackURL' => $data['callback_url'],
            'Method' => $data['payment_method'],
            // 'TokenType' => 'OCP',
        ];
        $url_params = '';
        foreach($get_data as $key => $val) {
            $and = ($key == 'Method')? '' : '&';
            
            $url_params .= "$key=$val$and";
        }
        
        return $url_params;

    }
}