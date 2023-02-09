<?php

namespace Job;

use AuditTrail;
use Carbon\Carbon;
use City;
use Company;
use Country;
use Developer;
use Dun;
use Exception;
use Facility;
use Files;
use FileSyncLog;
use Helper\KCurl;
use HouseScheme;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Management;
use Monitoring;
use OtherDetails;
use Park;
use Parliment;
use Role;
use State;
use Strata;
use User;

class FilesSync
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
            $user_id = $data['user_id'];

            // log
            $fileLog = FileSyncLog::create([
                'data' => json_encode($file),
                'reference_file_id' => $file['id'],
                'status' => 'pending',
            ]);

            $council = Company::where('short_name', $council_code)->first();
            if ($council && !empty($file)) {

                $file = Files::updateOrCreate(
                    [
                        'company_id' => $council->id,
                        'file_no' => $file['file_no'],
                    ],
                    [
                        'year' => $file['year'],
                        'is_active' => $file['is_active'],
                        'is_deleted' => $file['is_deleted'],
                        'status' => $file['status'],
                        'approved_by' => (!empty($user_id) ? $user_id : ''),
                        'approved_at' => (!empty($file['approved_at']) ? date('Y-m-d H:i:s', strtotime($file['approved_at'])) : date('Y-m-d H:i:s')),
                        'remarks' => $file['remarks'],
                        'created_by' => (!empty($user_id) ? $user_id : ''),
                        'created_at' => (!empty($file['created_at']) ? date('Y-m-d H:i:s', strtotime($file['created_at'])) : date('Y-m-d H:i:s')),
                        'updated_at' => (!empty($file['updated_at']) ? date('Y-m-d H:i:s', strtotime($file['updated_at'])) : date('Y-m-d H:i:s')),
                    ]
                );

                $this->auditTrail($remarks = $file->file_no . ' has been synced.', $user_id);

                /**
                 * House Scheme
                 */
                $this->houseScheme($file->id);





                // $exist_file = Files::where('company_id', $council->id)
                //     ->where('file_no', $file['file_no'])
                //     ->where('is_deleted', 0)
                //     ->first();

                // if (!$exist_file) {


                //     $exist_file = new Files();
                //     $exist_file->company_id = $council->id;
                //     $exist_file->file_no = $file['file_no'];
                //     $exist_file->year = $file['year'];
                //     $exist_file->is_active = $file['is_active'];
                //     $exist_file->is_deleted = $file['is_deleted'];
                //     $exist_file->status = $file['status'];
                //     $exist_file->approved_by = (!empty($superadmin) ? $superadmin->id : '');
                //     $exist_file->approved_at = $file['approved_at'];
                //     $exist_file->remarks = $file['remarks'];
                //     $exist_file->created_by = (!empty($superadmin) ? $superadmin->id : '');
                //     $exist_file->created_at = date('Y-m-d H:i:s', strtotime($file['created_at']));
                //     $exist_file->updated_at = date('Y-m-d H:i:s', strtotime($file['updated_at']));
                //     $success = $exist_file->save();

                //     if ($success) {
                //         $new_house_scheme = new HouseScheme();
                //         $new_house_scheme->file_id = $exist_file->id;
                //         $new_house_scheme->is_active = "1";
                //         $new_house_scheme->save();

                //         $new_strata = new Strata();
                //         $new_strata->file_id = $exist_file->id;
                //         if (!empty($strata)) {
                //             $new_strata->name = $strata['name'];
                //             $new_strata->title = $strata['title'];
                //             $new_strata->address1 = $strata['address1'];
                //             $new_strata->address2 = $strata['address2'];
                //             $new_strata->address3 = $strata['address3'];
                //             $new_strata->address4 = $strata['address4'];
                //             $new_strata->poscode = $strata['poscode'];
                //             $new_strata->block_no = $strata['block_no'];
                //             $new_strata->total_floor = $strata['total_floor'];
                //             $new_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                //             $new_strata->ownership_no = $strata['ownership_no'];
                //             $new_strata->land_area = $strata['land_area'];
                //             $new_strata->lot_no = $strata['lot_no'];
                //             $new_strata->total_share_unit = $strata['total_share_unit'];
                //             $new_strata->ccc_no = $strata['ccc_no'];
                //             $new_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                //             $new_strata->is_residential = $strata['is_residential'];
                //             $new_strata->is_commercial = $strata['is_commercial'];
                //             $new_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                //             $new_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                //         }
                //         $new_strata->save();

                //         $new_facility = new Facility();
                //         $new_facility->file_id = $exist_file->id;
                //         $new_facility->strata_id = $new_strata->id;
                //         $new_facility->save();

                //         $new_management = new Management();
                //         $new_management->file_id = $exist_file->id;
                //         $new_management->save();

                //         $new_monitor = new Monitoring();
                //         $new_monitor->file_id = $exist_file->id;
                //         $new_monitor->save();

                //         $new_others = new OtherDetails();
                //         $new_others->file_id = $exist_file->id;
                //         $new_others->save();
                //     }
                // } else {
                //     $exist_file->year = $file['year'];
                //     $exist_file->is_active = $file['is_active'];
                //     $exist_file->is_deleted = $file['is_deleted'];
                //     $exist_file->status = $file['status'];
                //     $exist_file->remarks = $file['remarks'];
                //     $exist_file->created_at = date('Y-m-d H:i:s', strtotime($file['created_at']));
                //     $exist_file->updated_at = date('Y-m-d H:i:s', strtotime($file['updated_at']));
                //     $exist_file->save();

                //     $old_strata = Strata::where('file_id', $exist_file->id)->first();
                //     if (!$old_strata) {
                //         $new_strata = new Strata();
                //         $new_strata->file_id = $exist_file->id;
                //         if (!empty($strata)) {
                //             $new_strata->name = $strata['name'];
                //             $new_strata->title = $strata['title'];
                //             $new_strata->address1 = $strata['address1'];
                //             $new_strata->address2 = $strata['address2'];
                //             $new_strata->address3 = $strata['address3'];
                //             $new_strata->address4 = $strata['address4'];
                //             $new_strata->poscode = $strata['poscode'];
                //             $new_strata->block_no = $strata['block_no'];
                //             $new_strata->total_floor = $strata['total_floor'];
                //             $new_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                //             $new_strata->ownership_no = $strata['ownership_no'];
                //             $new_strata->land_area = $strata['land_area'];
                //             $new_strata->lot_no = $strata['lot_no'];
                //             $new_strata->total_share_unit = $strata['total_share_unit'];
                //             $new_strata->ccc_no = $strata['ccc_no'];
                //             $new_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                //             $new_strata->is_residential = $strata['is_residential'];
                //             $new_strata->is_commercial = $strata['is_commercial'];
                //             $new_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                //             $new_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                //         }
                //         $new_strata->save();
                //     } else {
                //         if (!empty($strata)) {
                //             $old_strata->name = $strata['name'];
                //             $old_strata->title = $strata['title'];
                //             $old_strata->address1 = $strata['address1'];
                //             $old_strata->address2 = $strata['address2'];
                //             $old_strata->address3 = $strata['address3'];
                //             $old_strata->address4 = $strata['address4'];
                //             $old_strata->poscode = $strata['poscode'];
                //             $old_strata->block_no = $strata['block_no'];
                //             $old_strata->total_floor = $strata['total_floor'];
                //             $old_strata->year = (!empty($strata['year']) ? $strata['year'] : null);
                //             $old_strata->ownership_no = $strata['ownership_no'];
                //             $old_strata->land_area = $strata['land_area'];
                //             $old_strata->lot_no = $strata['lot_no'];
                //             $old_strata->total_share_unit = $strata['total_share_unit'];
                //             $old_strata->ccc_no = $strata['ccc_no'];
                //             $old_strata->ccc_date = ($strata['ccc_date'] != '0000-00-00' ? $strata['ccc_date'] : null);
                //             $old_strata->is_residential = $strata['is_residential'];
                //             $old_strata->is_commercial = $strata['is_commercial'];
                //             $old_strata->created_at = date('Y-m-d H:i:s', strtotime($strata['created_at']));
                //             $old_strata->updated_at = date('Y-m-d H:i:s', strtotime($strata['updated_at']));
                //             $old_strata->save();
                //         }
                //     }
                // }

                // curl to get data
                // if (!empty($council->short_name) && !empty($exist_file->file_no)) {
                //     $fileLog->update([
                //         'file_id' => $exist_file->id,
                //         'status' => 'success',
                //     ]);

                //     $path = 'financeFile?council_code=' . $council->short_name . '&file_no=' . urlencode($exist_file->file_no);
                //     $finances = json_decode($this->curl($path));

                //     if (!empty($finances)) {
                //         $delay = 1;
                //         $incrementDelay = 2;

                //         foreach ($finances as $finance) {
                //             $data = [
                //                 'council_code' => $council->short_name,
                //                 'file_no' => $exist_file->file_no,
                //                 'finance' => $finance
                //             ];

                //             try {
                //                 Queue::later(Carbon::now()->addSeconds($delay), FinanceSync::class, $data);
                //             } catch (Exception $e) {
                //                 Log::error($e);
                //             }

                //             $delay += $incrementDelay;
                //         }
                //     }
                // }
            }
        }

        $job->delete();
    }

    public function houseScheme($fileID)
    {
        $path = 'filesHouseScheme?file_id=' . $fileID;
        $data = json_decode($this->curl($path));

        if (!empty($data)) {
            if (!empty($data['developer'])) {
                if (!empty($data['developer']['city'])) {
                    $developer_city = City::updateOrCreate(
                        [
                            'description' => $data['developer']['city']['description'],
                        ],
                        [
                            'sort_no' => $data['developer']['city']['sort_no'],
                            'is_active' => 1,
                            'is_deleted' => 0,
                        ]
                    );
                }

                if (!empty($data['developer']['state'])) {
                    $developer_state = State::updateOrCreate(
                        [
                            'name' => $data['developer']['state']['name'],
                        ],
                        [
                            'code' => $data['developer']['state']['code'],
                            'sort_no' => $data['developer']['state']['sort_no'],
                            'is_active' => 1,
                            'is_deleted' => 0,
                        ]
                    );
                }

                if (!empty($data['developer']['country'])) {
                    $developer_country = Country::updateOrCreate(
                        [
                            'name' => $data['developer']['country']['name'],
                        ],
                        [
                            'sort_no' => $data['developer']['country']['sort_no'],
                            'is_active' => 1,
                            'is_deleted' => 0,
                        ]
                    );
                }

                $developer = Developer::updateOrCreate(
                    [
                        'name' => $data['developer']['name'],
                    ],
                    [
                        'phone_no' => $data['developer']['phone_no'],
                        'fax_no' => $data['developer']['fax_no'],
                        'address1' => $data['developer']['address1'],
                        'address2' => $data['developer']['address2'],
                        'address3' => $data['developer']['address3'],
                        'address4' => $data['developer']['address4'],
                        'city' => ($developer_city ? $developer_city->id : 0),
                        'poscode' => $data['developer']['poscode'],
                        'state' => ($developer_state ? $developer_state->id : 0),
                        'country' => ($developer_country ? $developer_country->id : 0),
                        'remarks' => $data['developer']['remarks'],
                        'is_active' => $data['developer']['is_active'],
                        'is_deleted' => $data['developer']['is_deleted'],
                        'created_at' => (!empty($data['developer']['created_at']) ? date('Y-m-d H:i:s', strtotime($data['developer']['created_at'])) : date('Y-m-d H:i:s')),
                        'updated_at' => (!empty($data['developer']['updated_at']) ? date('Y-m-d H:i:s', strtotime($data['developer']['updated_at'])) : date('Y-m-d H:i:s')),
                    ]
                );
            }
            /**
             * Developer End
             */

            if (!empty($data['liquidator_city'])) {
                $liquidator_city = City::updateOrCreate(
                    [
                        'description' => $data['liquidator_city']['description'],
                    ],
                    [
                        'sort_no' => $data['liquidator_city']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['liquidator_state'])) {
                $liquidator_state = State::updateOrCreate(
                    [
                        'name' => $data['liquidator_state']['name'],
                    ],
                    [
                        'code' => $data['liquidator_state']['code'],
                        'sort_no' => $data['liquidator_state']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['liquidator_country'])) {
                $liquidator_country = Country::updateOrCreate(
                    [
                        'name' => $data['liquidator_country']['name'],
                    ],
                    [
                        'sort_no' => $data['liquidator_country']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }
            /**
             * Liquidator End
             */

            if (!empty($data['city'])) {
                $city = City::updateOrCreate(
                    [
                        'description' => $data['city']['description'],
                    ],
                    [
                        'sort_no' => $data['city']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['state'])) {
                $state = State::updateOrCreate(
                    [
                        'name' => $data['state']['name'],
                    ],
                    [
                        'code' => $data['state']['code'],
                        'sort_no' => $data['state']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['country'])) {
                $country = Country::updateOrCreate(
                    [
                        'name' => $data['country']['name'],
                    ],
                    [
                        'sort_no' => $data['country']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            HouseScheme::updateOrCreate(
                [
                    'file_id' => $fileID,
                ],
                [
                    'name' => $data['name'],
                    'developer' => ($developer ? $developer->id : 0),
                    'liquidator' => $data['liquidator'],
                    'address1' => $data['address1'],
                    'address2' => $data['address2'],
                    'address3' => $data['address3'],
                    'address4' => $data['address4'],
                    'poscode' => $data['poscode'],
                    'city' => ($city ? $city->id : 0),
                    'state' => ($state ? $state->id : 0),
                    'country' => ($country ? $country->id : 0),
                    'phone_no' => $data['phone_no'],
                    'fax_no' => $data['fax_no'],
                    'liquidator_name' => $data['liquidator_name'],
                    'liquidator_address1' => $data['liquidator_address1'],
                    'liquidator_address2' => $data['liquidator_address2'],
                    'liquidator_address3' => $data['liquidator_address3'],
                    'liquidator_address4' => $data['liquidator_address4'],
                    'liquidator_poscode' => $data['liquidator_poscode'],
                    'liquidator_city' => ($liquidator_city ? $liquidator_city->id : 0),
                    'liquidator_state' => ($liquidator_state ? $liquidator_state->id : 0),
                    'liquidator_country' => ($liquidator_country ? $liquidator_country->id : 0),
                    'liquidator_phone_no' => $data['liquidator_phone_no'],
                    'liquidator_fax_no' => $data['liquidator_fax_no'],
                    'liquidator_is_active' => $data['liquidator_is_active'],
                    'liquidator_remarks' => $data['liquidator_remarks'],
                    'is_liquidator' => $data['is_liquidator'],
                    'is_active' => $data['is_active'],
                    'is_deleted' => $data['is_deleted'],
                    'remarks' => $data['remarks'],
                    'created_at' => (!empty($data['created_at']) ? date('Y-m-d H:i:s', strtotime($data['created_at'])) : date('Y-m-d H:i:s')),
                    'updated_at' => (!empty($data['updated_at']) ? date('Y-m-d H:i:s', strtotime($data['updated_at'])) : date('Y-m-d H:i:s')),
                ]
            );
        }
    }

    public function strata($fileID)
    {
        $path = 'filesStrata?file_id=' . $fileID;
        $data = json_decode($this->curl($path));

        if (!empty($data)) {
            if (!empty($data['parliament'])) {
                $parliament = Parliment::updateOrCreate(
                    [
                        'description' => $data['parliament']['description'],
                    ],
                    [
                        'code' => $data['parliament']['code'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['dun'])) {
                $dun = Dun::updateOrCreate(
                    [
                        'description' => $data['dun']['description'],
                    ],
                    [
                        'code' => $data['dun']['code'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['park'])) {
                $park = Park::updateOrCreate(
                    [
                        'dun' => $dun->id,
                        'description' => $data['park']['description'],
                    ],
                    [
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            if (!empty($data['city'])) {
                $city = Park::updateOrCreate(
                    [
                        'description' => $data['city']['description'],
                    ],
                    [
                        'sort_no' => $data['city']['sort_no'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                    ]
                );
            }

            Strata::updateOrCreate(
                [
                    'file_id' => $fileID,
                ],
                [
                    'name' => $data['name'],
                    'title' => $data['title'],
                    'parliament' => ($parliament ? $parliament->id : 0),
                    'dun' => ($dun ? $dun->id : 0),
                    'park' => ($park ? $park->id : 0),
                    'address1' => $data['address1'],
                    'address2' => $data['address2'],
                    'address3' => $data['address3'],
                    'address4' => $data['address4'],
                    'city' => $data[''],
                    '' => $data[''],
                    '' => $data[''],
                    '' => $data[''],
                    '' => $data[''],
                    '' => $data[''],
                ]
            );
        }
    }

    public function auditTrail($remarks, $user_id) {
        # Audit Trail
        $auditTrail = new AuditTrail();
        $auditTrail->module = "COB File";
        $auditTrail->remarks = $remarks;
        $auditTrail->audit_by = $user_id;
        $auditTrail->save();
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
