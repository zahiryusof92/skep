<?php

use Carbon\Carbon;
use Helper\KCurl;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Queue;
use Job\AgmMinuteSync;
use Job\BuyerSync;

class CobSyncController extends BaseController
{

    public function __construct()
    {
        $this->api_domain = Config::get('constant.third_party.eagm.api_domain');
        $this->oauth_client_id = Config::get('constant.third_party.eagm.oauth_client_id');
        $this->oauth_client_secret = Config::get('constant.third_party.eagm.oauth_client_secret');
        $this->oauth_username = Config::get('constant.third_party.eagm.oauth_username');
        $this->oauth_password = Config::get('constant.third_party.eagm.oauth_password');
    }

    /**
     * return specific resource to the veiw
     */
    public function index()
    {
        if (Auth::user()->getAdmin()) {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->get();
            }

            $viewData = array(
                'title' => 'COB Sync',
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_sync',
                'cob' => $cob,
                'api_domain' => $this->api_domain . 'api/v1/',
            );
            
            return View::make('cob_en.cob_sync', $viewData);
        }

        return $this->errorPage();
    }

    /**
     * Get property option from eAGM
     * @param - code
     */
    public function getProperty()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $validation_rules = [
                'code' => 'required',
            ];

            $validator = \Validator::make($data, $validation_rules, []);

            if ($validator->fails()) {
                $errors = $validator->errors();

                return [
                    'status' => 422,
                    'data' => $errors->toJson(),
                    'message' => 'Validation Error'
                ];
            }
            $code = $data['code'];

            $url = $this->api_domain . 'api/v1/property/get-option?code=' . $code;

            $response = json_decode((string) ((new KCurl())->requestGET(
                $this->getHeader(),
                $url
            )));

            if (empty($response->success) == false && $response->success == true) {
                return [
                    'status' => 200,
                    'data' => $response->data
                ];
            } else {
                return [
                    'status' => 422,
                    'message' => $response->message
                ];
            }
        }
    }

    /**
     * Get params and process data synchronize
     * @param -property_id, file_id
     */
    public function submitBuyerSync()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $validation_rules = [
                'eagm_property' => 'required|alpha_num',
                'file_no' => 'required|alpha_num',
                'company' => 'required',
            ];

            $validator = \Validator::make($data, $validation_rules, []);

            if ($validator->fails()) {
                $errors = $validator->errors();

                return [
                    'status' => 422,
                    'data' => $errors->toJson(),
                    'message' => 'Validation Error'
                ];
            }

            $property_id = $data['eagm_property'];
            $file_id = $data['file_no'];
            $code = $data['company'];

            // curl to get data
            $url = $this->api_domain . 'api/v1/unitUser/get?code=' . $code . '&property_id=' . $property_id;
            $response = json_decode((string) ((new KCurl())->requestGET(
                $this->getHeader(),
                $url
            )));

            if (empty($response->success) == false && $response->success == true) {
                $items = $response->data;
                $delay = 0;
                $incrementDelay = 2;
                foreach ($items as $item) {
                    Queue::later(Carbon::now()->addSeconds($delay), BuyerSync::class, array('item' => $item, 'file_id' => $file_id));
                    $delay += $incrementDelay;
                }

                // sync agm minutes
                // curl to get data
                $url_submission = $this->api_domain . 'api/v1/agmSubmission/get?code=' . $code . '&property_id=' . $property_id;
                $response_submission = json_decode((string) ((new KCurl())->requestGET(
                    $this->getHeader(),
                    $url_submission
                )));

                if (empty($response_submission->success) == false && $response_submission->success == true) {
                    $items_submission = $response_submission->data;
                    $delay_submission = 0;
                    $incrementDelay_submission = 2;
                    foreach ($items_submission as $item_submission) {
                        Queue::later(Carbon::now()->addSeconds($delay_submission), AgmMinuteSync::class, array('item' => $item_submission, 'file_id' => $file_id));
                        $delay_submission += $incrementDelay_submission;
                    }
                }
            } else {
                return [
                    'status' => 422,
                    'message' => $response->message
                ];
            }

            return [
                'status' => 200
            ];
        }
    }

    public function errorPage()
    {
        $viewData = array(
            'title' => trans('app.errors.page_not_found'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'image' => ""
        );

        return View::make('404_en', $viewData);
    }

    /**
     * get and check token validity
     */
    public function getAccessToken()
    {
        $token_data = EagmOauth::orderBy('created_at', 'desc')->first();

        if (empty($token_data)) {
            $data = [
                'grant_type' => 'password',
                'client_id' => $this->oauth_client_id,
                'client_secret' => $this->oauth_client_secret,
                'username' => $this->oauth_username,
                'password' => $this->oauth_password,
                'scope' => '*'
            ];

            $url = $this->api_domain . 'oauth/token';
            $header = [
                "Accept: application/json",
            ];
            $response = json_decode((string) ((new KCurl())->requestPOST(
                $header,
                $url,
                $data
            )));

            $oauth = new EagmOauth;
            $oauth->access_token = $response->access_token;
            $oauth->refresh_token = $response->refresh_token;
            $oauth->expire_in = $response->expires_in;
            $oauth->save();

            return $oauth->access_token;
        } else {

            if (Carbon::now()->timestamp >= ($token_data->expire_in + Carbon::parse($token_data->updated_at)->timestamp)) {
                $data = [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->oauth_client_id,
                    'client_secret' =>   $this->oauth_client_secret,
                    'refresh_token' => $token_data->refresh_token,
                    'scope' => '*'
                ];

                $url = $this->api_domain . 'oauth/token';
                $header = [
                    "Accept: application/json",
                ];
                $response = json_decode((string) ((new KCurl())->requestPOST(
                    $header,
                    $url,
                    $data
                )));

                $token_data->access_token = $response->access_token;
                $token_data->refresh_token = $response->refresh_token;
                $token_data->expire_in = $response->expires_in;
                $token_data->save();

                return $token_data->access_token;
            }

            return $token_data->access_token;
        }
    }

    public function getHeader()
    {
        return [
            'Authorization: Bearer ' . $this->getAccessToken(),
            "Accept: application/json",
        ];
    }
}
