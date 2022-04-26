<?php

namespace Job;

use Carbon\Carbon;
use Exception;
use Helper\KCurl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class MPSSync
{
    public $api_domain;

    public function __construct()
    {
        $this->api_domain = 'https://ecob.mps.gov.my/api/v4/';
    }

    public function fire($job, $data)
    {
        // Process the job...
        if (!empty($data)) {
            $council_code = $data['council_code'];
            $page = $data['page'];

            $path = 'files?council_code=' . $council_code . '&page=' . $page;
            $files = json_decode($this->curl($path));

            if (!empty($files) && !empty($files->data)) {
                $delay = 1;
                $incrementDelay = 2;

                foreach ($files->data as $file) {
                    $data = [
                        'council_code' => $council_code,
                        'file' => $file,
                    ];

                    try {
                        Queue::later(Carbon::now()->addSeconds($delay), FileSync::class, $data);
                    } catch (Exception $e) {
                        Log::error($e);
                    }

                    $delay += $incrementDelay;
                }
            }
        }

        $job->delete();
    }

    public function getHeader()
    {
        return [
            "Accept: application/json",
        ];
    }

    public function curl($path)
    {
        // curl to get data
        $url = $this->api_domain . $path;
        $response = json_decode((string) ((new KCurl())->requestGET($this->getHeader(), $url)));

        if (empty($response->success) == false && $response->success == true) {
            $items = $response->data;

            return json_encode($items);
        }

        return false;
    }
}
