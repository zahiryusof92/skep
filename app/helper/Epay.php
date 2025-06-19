<?php

namespace Helper;

use EServiceController;
use EServiceOrder;
use Helper\KCurl;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Epay
{
    protected $clientid;
    protected $secretkey;
    protected $endpoint_url;
    protected $payment_gateway_url;
    protected $payment_gateway_secret_id;
    protected $payment_gateway_secret_key;
    protected $module;

    public function __construct()
    {
        $this->clientid = Config::get('payment.mbpj.client_id');
        $this->secretkey = Config::get('payment.mbpj.secret_key');
        $this->endpoint_url = Config::get('payment.mbpj.endpoint_url');
        $this->payment_gateway_url = Config::get('payment.mbpj.payment_gateway_url');
        $this->payment_gateway_secret_id = Config::get('payment.mbpj.payment_gateway_secret_id');
        $this->payment_gateway_secret_key = Config::get('payment.mbpj.payment_gateway_secret_key');
        $this->module = Config::get('constant.module');
    }

    public function getHeader()
    {
        $header = ["Content-Type: application/json"];
        if (!empty($this->clientid)) {
            array_push($header, "MBPJ-ClientID: {$this->clientid}");
        }

        return $header;
    }

    public function generateSignature($params = [])
    {
        $signature = '';

        if (!empty($params)) {
            $clientid = $this->clientid;
            $secretkey = $this->secretkey;
            $kodjabatan = Arr::get($params, 'kodjabatan');
            $perkara = Arr::get($params, 'perkara');
            $amaun = Arr::get($params, 'amaun');
            $kodhasil = Arr::get($params, 'kodhasil');
            $namapelanggan = Arr::get($params, 'namapelanggan');
            $alamat1 = Arr::get($params, 'alamat1');
            $alamat2 = Arr::get($params, 'alamat2');
            $alamat3 = Arr::get($params, 'alamat3');
            $nokp = Arr::get($params, 'nokp');
            $pengguna = Arr::get($params, 'pengguna');
            $sumber = Arr::get($params, 'sumber');

            $signature = md5($clientid . $kodjabatan . $perkara . $amaun . $kodhasil . $namapelanggan . $alamat1 . $alamat2 . $alamat3 . $nokp . $pengguna . $sumber . $secretkey);
        }

        return $signature;
    }

    public function getAPIHeader($params)
    {
        $header = $this->getHeader();
        $signature = $this->generateSignature($params);
        if (!empty($signature)) {
            array_push($header, "MBPJ-Signature: {$signature}");
        }

        return $header;
    }

    public function generateToken($params = [])
    {
        $token = '';

        if (!empty($params)) {
            $secret = Arr::get($params, 'secret');
            $pg_client = Arr::get($params, 'pg_client');
            $pg_ref_id = Arr::get($params, 'pg_ref_id');
            $pg_action = Arr::get($params, 'pg_action');

            if ($pg_action == 'reconcile') {
                $token = md5($secret . $pg_client . $pg_ref_id . $pg_action);
            } else {
                $token = md5($secret . $pg_client . $pg_ref_id);
            }
        }

        return $token;
    }

    public function generateBil($params)
    {
        try {
            $response = (new KCurl())->requestPost($this->getAPIHeader($params), $this->endpoint_url, json_encode($params));

            return json_decode($response);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());

            return false;
        }
    }

    public function paymentOnline($account_no, $orderId)
    {
        $response = '';

        $order = EServiceOrder::find($this->generateDecodeID($orderId));
        if ($order && $order->status == EServiceOrder::DRAFT) {

            // update reference_id every time need to do payment
            $pg_ref_id = $order->order_no . '-' . date('His');
            $pg_client = $this->payment_gateway_secret_id;
            $pg_amount = number_format($order->price, 2);
            $pg_account_no = $account_no;
            $pg_revenue_code = Config::get('payment.mbpj.kod_hasil');
            $pg_dept_code = Config::get('payment.mbpj.kod_jabatan');
            $pg_return_url = route('eservice.callbackPayment', $this->generateEncodeID($order->id));

            $params_token = [
                'secret' => $this->payment_gateway_secret_key,
                'pg_client' => $pg_client,
                'pg_ref_id' => $pg_ref_id,
            ];
            $token = $this->generateToken($params_token);

            $param['pg_client'] = $pg_client; // BilPelbagai (client id)
            $param['pg_ref_id'] = $pg_ref_id; // MBPJ-eCOB-ref_no (client ref id) (max 50 chars) 
            $param['pg_amount'] = $pg_amount; // (amount to pay in 2 point decimal) 
            $param['pg_account_no'] = $pg_account_no;
            $param['pg_revenue_code'] = $pg_revenue_code; // (5 chars) 
            $param['pg_dept_code'] = $pg_dept_code;
            $param['pg_return_url'] =  $pg_return_url; // (client url to post response data once payment successful or failed)
            $param['pg_token'] = $token; // (token to validate data)

            $post_data = http_build_query($param);
            $response = $this->payment_gateway_url . '?' . $post_data;

            $eserviceLog = new Logger('eservice');
            $eserviceLog->pushHandler(new StreamHandler(storage_path('logs/eservice.log'), Logger::INFO));
            $eserviceLog->info('Payment Submitted:', ['order_id' => $order->id, 'url' => $response]);
        }

        return $response;
    }

    public function reconcile($orderId) {
        $response = '';

        $order = EServiceOrder::find($this->generateDecodeID($orderId));
        if ($order && !empty($order->reference_id)) {
            $pg_client = $this->payment_gateway_secret_id;
            $pg_ref_id = $order->reference_id;

            $params_token = [
                'secret' => $this->payment_gateway_secret_key,
                'pg_client' => $pg_client,
                'pg_ref_id' => $pg_ref_id,
                'pg_action' => 'reconcile',
            ];
            $token = $this->generateToken($params_token);

            $param['pg_action'] = 'reconcile'; // reconcile
            $param['pg_client'] = $pg_client; // BilPelbagai (client id)
            $param['pg_ref_id'] = $pg_ref_id; // MBPJ-eCOB-ref_no (client ref id) (max 50 chars) 
            $param['pg_token'] = $token; // (token to validate data)

            $post_data = http_build_query($param);
            $url = $this->payment_gateway_url . '?' . $post_data;

            try {
                Arr::set($response, 'status', true);
                Arr::set($response, 'response', (new KCurl())->requestGET($this->getHeader(), $url));
            } catch (\Throwable $e) {
                Arr::set($response, 'status', false);
                \Log::error($e->getMessage());
            }
        }

        return $response;
    }

    private function getModule()
    {
        return $this->module['eservice'];
    }

    private function generateEncodeID($id)
    {
        return Helper::encode($this->getModule()['name'], $id);
    }

    private function generateDecodeID($id)
    {
        return Helper::decode($id, $this->getModule()['name']);
    }
}
