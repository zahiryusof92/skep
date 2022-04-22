<?php

namespace Job;

use Carbon\Carbon;
use Company;
use Facility;
use Files;
use FileSyncLog;
use Helper\KCurl;
use HouseScheme;
use Illuminate\Support\Facades\Queue;
use Management;
use Monitoring;
use OtherDetails;
use Role;
use Strata;
use User;

class FileSync
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
            $file = $data['file'];

            // log
            $fileLog = FileSyncLog::create([
                'data' => json_encode($file),
                'reference_file_id' => $file['id'],
                'status' => 'pending',
            ]);

            $council = Company::where('short_name', $council_code)->first();
            if ($council && !empty($file)) {
                $strata = $file['strata'];

                $exist_file = Files::where('company_id', $council->id)
                    ->where('file_no', $file['file_no'])
                    ->where('is_deleted', 0)
                    ->first();

                if (!$exist_file) {
                    $superadmin = User::where('role', Role::SUPERADMIN)
                        ->where('is_deleted', 0)
                        ->first();

                    $exist_file = new Files();
                    $exist_file->company_id = $council->id;
                    $exist_file->file_no = $file['file_no'];
                    $exist_file->year = $file['year'];
                    $exist_file->is_active = $file['is_active'];
                    $exist_file->is_deleted = $file['is_deleted'];
                    $exist_file->status = $file['status'];
                    $exist_file->approved_by = (!empty($superadmin) ? $superadmin->id : '');
                    $exist_file->approved_at = $file['approved_at'];
                    $exist_file->remarks = $file['remarks'];
                    $exist_file->created_by = (!empty($superadmin) ? $superadmin->id : '');
                    $exist_file->created_at = date('Y-m-d H:i:s', strtotime($file['created_at']));
                    $exist_file->updated_at = date('Y-m-d H:i:s', strtotime($file['updated_at']));
                    $success = $exist_file->save();

                    if ($success) {
                        $new_house_scheme = new HouseScheme();
                        $new_house_scheme->file_id = $exist_file->id;
                        $new_house_scheme->is_active = "1";
                        $new_house_scheme->save();

                        $new_strata = new Strata();
                        $new_strata->file_id = $exist_file->id;
                        if (!empty($strata)) {
                            $new_strata->name = $strata['name'];
                            $new_strata->title = $strata['title'];
                            $new_strata->address1 = $strata['address1'];
                            $new_strata->address2 = $strata['address2'];
                            $new_strata->address3 = $strata['address3'];
                            $new_strata->address4 = $strata['address4'];
                            $new_strata->poscode = $strata['poscode'];
                            $new_strata->block_no = $strata['block_no'];
                            $new_strata->total_floor = $strata['total_floor'];
                            $new_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                            $new_strata->ownership_no = $strata['ownership_no'];
                            $new_strata->land_area = $strata['land_area'];
                            $new_strata->lot_no = $strata['lot_no'];
                            $new_strata->total_share_unit = $strata['total_share_unit'];
                            $new_strata->ccc_no = $strata['ccc_no'];
                            $new_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                            $new_strata->is_residential = $strata['is_residential'];
                            $new_strata->is_commercial = $strata['is_commercial'];
                            $new_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                            $new_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                        }
                        $new_strata->save();

                        $new_facility = new Facility();
                        $new_facility->file_id = $exist_file->id;
                        $new_facility->strata_id = $new_strata->id;
                        $new_facility->save();

                        $new_management = new Management();
                        $new_management->file_id = $exist_file->id;
                        $new_management->save();

                        $new_monitor = new Monitoring();
                        $new_monitor->file_id = $exist_file->id;
                        $new_monitor->save();

                        $new_others = new OtherDetails();
                        $new_others->file_id = $exist_file->id;
                        $new_others->save();
                    }
                } else {
                    $exist_file->year = $file['year'];
                    $exist_file->is_active = $file['is_active'];
                    $exist_file->is_deleted = $file['is_deleted'];
                    $exist_file->status = $file['status'];
                    $exist_file->remarks = $file['remarks'];
                    $exist_file->created_at = date('Y-m-d H:i:s', strtotime($file['created_at']));
                    $exist_file->updated_at = date('Y-m-d H:i:s', strtotime($file['updated_at']));
                    $exist_file->save();

                    $old_strata = Strata::where('file_id', $exist_file->id)->first();
                    if (!$old_strata) {
                        $new_strata = new Strata();
                        $new_strata->file_id = $exist_file->id;
                        if (!empty($strata)) {
                            $new_strata->name = $strata['name'];
                            $new_strata->title = $strata['title'];
                            $new_strata->address1 = $strata['address1'];
                            $new_strata->address2 = $strata['address2'];
                            $new_strata->address3 = $strata['address3'];
                            $new_strata->address4 = $strata['address4'];
                            $new_strata->poscode = $strata['poscode'];
                            $new_strata->block_no = $strata['block_no'];
                            $new_strata->total_floor = $strata['total_floor'];
                            $new_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                            $new_strata->ownership_no = $strata['ownership_no'];
                            $new_strata->land_area = $strata['land_area'];
                            $new_strata->lot_no = $strata['lot_no'];
                            $new_strata->total_share_unit = $strata['total_share_unit'];
                            $new_strata->ccc_no = $strata['ccc_no'];
                            $new_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                            $new_strata->is_residential = $strata['is_residential'];
                            $new_strata->is_commercial = $strata['is_commercial'];
                            $new_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                            $new_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                        }
                        $new_strata->save();
                    } else {
                        if (!empty($strata)) {
                            $old_strata->name = $strata['name'];
                            $old_strata->title = $strata['title'];
                            $old_strata->address1 = $strata['address1'];
                            $old_strata->address2 = $strata['address2'];
                            $old_strata->address3 = $strata['address3'];
                            $old_strata->address4 = $strata['address4'];
                            $old_strata->poscode = $strata['poscode'];
                            $old_strata->block_no = $strata['block_no'];
                            $old_strata->total_floor = $strata['total_floor'];
                            $old_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                            $old_strata->ownership_no = $strata['ownership_no'];
                            $old_strata->land_area = $strata['land_area'];
                            $old_strata->lot_no = $strata['lot_no'];
                            $old_strata->total_share_unit = $strata['total_share_unit'];
                            $old_strata->ccc_no = $strata['ccc_no'];
                            $old_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                            $old_strata->is_residential = $strata['is_residential'];
                            $old_strata->is_commercial = $strata['is_commercial'];
                            $old_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                            $old_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                            $old_strata->save();
                        }
                    }
                }

                // curl to get data
                if (!empty($council->short_name) && !empty($exist_file->file_no)) {
                    $fileLog->update([
                        'file_id' => $exist_file->id,
                        'status' => 'success',
                    ]);

                    $path = 'financeFile?council_code=' . $council->short_name . '&file_no=' . urlencode($exist_file->file_no);
                    $finances = json_decode($this->curl($path));

                    if (!empty($finances)) {
                        $delay = 0;
                        $incrementDelay = 2;

                        foreach ($finances as $finance) {
                            $data = [
                                'council_code' => $council->short_name,
                                'file_no' => $exist_file->file_no,
                                'finance' => $finance
                            ];

                            Queue::later(Carbon::now()->addSeconds($delay), FinanceSync::class, $data);

                            $delay += $incrementDelay;
                        }
                    }
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
