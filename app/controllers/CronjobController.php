<?php

class CronjobController extends BaseController {

    public function __construct() {
        $this->api_endpoint = 'http://test.kpkt.gov.my/ecob/api/v1/';
        $this->token_key = 'MOJl6ZwY62EQ43r0GMvqWDy5NdBoj1yunRxlKa1pLXA79J8njmgPzbOe7raP';
        $this->resident = 'R';
        $this->commercial = 'C';
        $this->agm = 'AGM';
        $this->egm = 'EGM';
        $this->debug = false;
        $this->success = 200;
        $this->error = 400;
    }

    public function createFileByCob($cob) {
        $response = '';

        if (!empty($cob)) {
            $council = Company::where('short_name', $cob)->where('is_deleted', 0)->first();
            if ($council) {
                $files = Files::where('company_id', $council->id)->orderBy('id')->take(3)->get();
                if ($files) {
                    return Response::json($files, $this->success);
                    foreach ($files as $file) {
                        $response[] = $this->createFile($file->id);
                    }
                }
            }
        }

        return Response::json($response, $this->success);
    }

    public function createFile($id) {
        $response = '';

        if (!empty($id)) {
            $file = Files::find($id);
            if (count($file) > 0) {
//                $response['postSkim'] = $this->postSkim($file);
//                $response['postSkimBlock'] = $this->postSkimBlock($file);
//                $response['postSkimFacility'] = $this->postSkimFacility($file);
//                $response['postSkimImage'] = $this->postSkimImage($file);
//                $response['postSkimMeeting'] = $this->postSkimMeeting($file);
                $response['postSkimFinancial'] = $this->postSkimFinancial($file);
//                $response['postSkimManagement'] = $this->postSkimManagement($file);
            }
        }

        return Response::json($response, $this->success);
    }

    public function updateFile($id) {
        $response = '';

        if (!empty($id)) {
            $file = Files::find($id);
            if (count($file) > 0) {
                $response['putSkim'] = $this->putSkim($file);
                $response['putSkimBlock'] = $this->putSkimBlock($file);
                $response['putSkimFacility'] = $this->putSkimFacility($file);
                $response['putSkimManagement'] = $this->putSkimManagement($file);
            }
        }

        return Response::json($response, $this->success);
    }

    public function deleteFile($id) {
        $response = '';

        if (!empty($id)) {
            $file = Files::find($id);
            if (count($file) > 0) {
                $response['deleteSkimFacility'] = $this->deleteSkimFacility($file);
            }
        }

        return Response::json($response, $this->success);
    }

    public function existingLog($file, $action, $method) {
        $existing_sync = SyncLog::where('file_id', $file->id)->where('api_endpoint', $this->api_endpoint)->where('action', $action)->where('method', $method)->where('status', $this->success)->count();

        return $existing_sync;
    }

    public function postSkim($file) {
        $response = '';
        $action = 'skim';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if ((!empty($file->strata->toArray()) && !empty($file->management->toArray())) && !empty($file->houseScheme->toArray())) {
                    $param['filing_no'] = $file->file_no;
                    $param['name'] = (!empty($file->strata->name) ? $file->strata->name : '(Not Available)');
                    $param['address'] = ($file->strata->address1 ? $file->strata->address1 . ', ' : '(Not Available)') . ($file->strata->address2 ? $file->strata->address2 . ', ' : '') . ($file->strata->address3 ? $file->strata->address3 . ', ' : '');
                    $param['postcode'] = (!empty($file->strata->poscode) ? $file->strata->poscode : 99999);
                    $param['state'] = (!empty($file->strata->state) ? $file->strata->states->code : 'B');
                    $param['parliament'] = ((!empty($file->strata->parliament) && !empty($file->strata->dun)) ? $file->strata->parliment->code : '');
                    $param['dun'] = ($file->strata->dun ? $file->strata->duns->code : '');
                    $param['district'] = ($file->strata->city ? $file->strata->cities->description : '');
                    $param['subdistrict'] = '';
                    $param['proprietary_type'] = '';
                    $param['proprietary_no'] = '';
                    $param['lot_no'] = $file->strata->lot_no;
                    $param['plot_no'] = '';
                    $param['total_lot'] = 0;
                    $param['total_block'] = ($file->strata->block_no ? (int) $file->strata->block_no : 0);
                    if ($file->strata->is_residential && $file->strata->is_commercial) {
                        $no_resident = ($file->resident ? (int) $file->resident->unit_no : 0);
                        $no_commercial = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                        $param['total_square'] = $no_resident + $no_commercial;
                    } else if ($file->strata->is_residential) {
                        $param['total_square'] = ($file->resident ? (int) $file->resident->unit_no : 0);
                    } else if ($file->strata->is_commercial) {
                        $param['total_square'] = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                    } else {
                        $param['total_square'] = 0;
                    }
                    $param['land_type'] = ($file->strata->land_title ? $file->strata->landTitle->code : 'R');
                    $param['development_type'] = 'R';
                    $param['category'] = ($file->strata->category ? $file->strata->categories->code : 'R');
                    $param['latitude'] = ($file->other->latitude > 0 ? $file->other->latitude : '');
                    $param['longitude'] = ($file->other->longitude > 0 ? $file->other->longitude : '');
                    if ($file->management->is_jmb) {
                        $param['management_type'] = 'JMB';
                    } else if ($file->management->is_mc) {
                        $param['management_type'] = 'MC';
                    } else if ($file->management->is_agent) {
                        $param['management_type'] = 'A';
                    } else {
                        $param['management_type'] = 'DEV';
                    }
                    $param['developer_name'] = ($file->houseScheme->developer ? $file->houseScheme->developers->name : '');
                    $param['developer_ssm'] = '';

                    if (!empty($param)) {
                        $response = $this->curl($file, $action, $method, $param);
                    }
                }
            }
        }

        return $response;
    }

    public function putSkim($file) {
        $response = '';
        $action = 'skim';
        $method = 'PUT';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, 'POST', $method);
            if ($existing_sync > 0) {
                if ((!empty($file->strata->toArray()) && !empty($file->management->toArray())) && !empty($file->houseScheme->toArray())) {
                    $param['filing_no'] = $file->file_no;
                    $param['name'] = $file->strata->name;
                    $param['address'] = ($file->strata->address1 ? $file->strata->address1 . ', ' : '') . ($file->strata->address2 ? $file->strata->address2 . ', ' : '') . ($file->strata->address3 ? $file->strata->address3 . ', ' : '');
                    $param['postcode'] = $file->strata->poscode;
                    $param['state'] = ($file->strata->state ? $file->strata->states->code : '');
                    $param['parliament'] = ($file->strata->parliament ? $file->strata->parliment->code : '');
                    $param['dun'] = ($file->strata->dun ? $file->strata->duns->code : '');
                    $param['district'] = ($file->strata->city ? $file->strata->cities->description : '');
                    $param['subdistrict'] = '';
                    $param['proprietary_type'] = '';
                    $param['proprietary_no'] = '';
                    $param['lot_no'] = $file->strata->lot_no;
                    $param['plot_no'] = '';
                    $param['total_lot'] = 0;
                    $param['total_block'] = ($file->strata->block_no ? (int) $file->strata->block_no : 0);
                    if ($file->strata->is_residential && $file->strata->is_commercial) {
                        $no_resident = ($file->resident ? (int) $file->resident->unit_no : 0);
                        $no_commercial = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                        $param['total_square'] = $no_resident + $no_commercial;
                    } else if ($file->strata->is_residential) {
                        $param['total_square'] = ($file->resident ? (int) $file->resident->unit_no : 0);
                    } else if ($file->strata->is_commercial) {
                        $param['total_square'] = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                    } else {
                        $param['total_square'] = 0;
                    }
                    $param['land_type'] = ($file->strata->land_title ? $file->strata->landTitle->code : '-');
                    $param['development_type'] = '';
                    $param['category'] = ($file->strata->category ? $file->strata->categories->code : '-');
                    $param['latitude'] = ($file->other->latitude > 0 ? $file->other->latitude : '');
                    $param['longitude'] = ($file->other->longitude > 0 ? $file->other->longitude : '');
                    if ($file->management->is_jmb) {
                        $param['management_type'] = 'JMB';
                    } else if ($file->management->is_mc) {
                        $param['management_type'] = 'MC';
                    } else if ($file->management->is_agent) {
                        $param['management_type'] = 'A';
                    } else {
                        $param['management_type'] = '';
                    }
                    $param['developer_name'] = ($file->houseScheme->developer ? $file->houseScheme->developers->name : '');
                    $param['developer_ssm'] = '';

                    if (!empty($param)) {
                        $response = $this->curl($file, $action, $method, $param);
                    }
                }
            }
        }

        return $response;
    }

    public function postSkimBlock($file) {
        $response = '';
        $action = 'skim-block';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if (!empty($file->strata->toArray())) {
                    $param['filing_no'] = $file->file_no;
                    $param['reference_key'] = $file->file_no;
                    $param['phase'] = 'Phase 1';
                    if ($file->strata->is_residential) {
                        $param['type'] = $this->resident;
                        $param['block_type'] = 'multi_storey';
                        $param['total_unit'] = ($file->resident ? (int) $file->resident->unit_no : 0);
                        $param['maintenance_fee'] = ($file->resident ? (float) $file->resident->maintenance_fee : 0);
                        $param['sinking_fund'] = ($file->resident ? (float) $file->resident->sinking_fund : 0);
                    } else if ($file->strata->is_commercial) {
                        $param['type'] = $this->commercial;
                        $param['block_type'] = 'service_apartment';
                        $param['total_unit'] = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                        $param['maintenance_fee'] = ($file->commercial ? (float) $file->commercial->maintenance_fee : 0);
                        $param['sinking_fund'] = ($file->commercial ? (float) $file->commercial->sinking_fund : 0);
                    } else {
                        $param['type'] = $this->resident;
                        $param['block_type'] = 'townhouse';
                        $param['total_unit'] = 0;
                        $param['maintenance_fee'] = 0;
                        $param['sinking_fund'] = 0;
                    }
                    $param['vp_date'] = ((!empty($file->strata) && $file->strata->date != '0000-00-00') ? $file->strata->date : date('Y-m-d'));
                    $param['dlp_end_date'] = date('Y-m-d');

                    if (!empty($param)) {
                        $response = $this->curl($file, $action, $method, $param);
                    }
                }
            }
        }

        return $response;
    }

    public function putSkimBlock($file) {
        $response = '';
        $action = 'skim-block';
        $method = 'PUT';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, 'POST', $method);
            if ($existing_sync > 0) {
                if (!empty($file->strata->toArray())) {
                    $param['filing_no'] = $file->file_no;
                    $param['reference_key'] = $file->file_no;
                    $param['phase'] = '';
                    if ($file->strata->is_residential) {
                        $param['type'] = $this->resident;
                        $param['block_type'] = '';
                        $param['total_unit'] = ($file->resident ? (int) $file->resident->unit_no : 0);
                        $param['maintenance_fee'] = ($file->resident ? (float) $file->resident->maintenance_fee : 0);
                        $param['sinking_fund'] = ($file->resident ? (float) $file->resident->sinking_fund : 0);
                    } else if ($file->strata->is_commercial) {
                        $param['type'] = $this->commercial;
                        $param['block_type'] = '';
                        $param['total_unit'] = ($file->commercial ? (int) $file->commercial->unit_no : 0);
                        $param['maintenance_fee'] = ($file->commercial ? (float) $file->commercial->maintenance_fee : 0);
                        $param['sinking_fund'] = ($file->commercial ? (float) $file->commercial->sinking_fund : 0);
                    } else {
                        $param['type'] = '';
                        $param['block_type'] = '';
                        $param['total_unit'] = 0;
                        $param['maintenance_fee'] = 0;
                        $param['sinking_fund'] = 0;
                    }
                    $param['vp_date'] = ($file->strata ? $file->strata->date : '');
                    $param['dlp_end_date'] = '';

                    if (!empty($param)) {
                        $response = $this->curl($file, $action, $method, $param);
                    }
                }
            }
        }

        return $response;
    }

    public function postSkimFacility($file) {
        $response = '';
        $action = 'skim-facility';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            if (!empty($file->facility->toArray())) {
                /*
                 * Pejabat Pengurusan
                 */
                if ($file->facility->management_office) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F01';
                    $param['total'] = $file->facility->management_office_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Kolam Renang
                 */
                if ($file->facility->swimming_pool) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F02';
                    $param['total'] = $file->facility->swimming_pool_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Surau
                 */
                if ($file->facility->surau) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F03';
                    $param['total'] = $file->facility->surau_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Dewan Serbaguna
                 */
                if ($file->facility->multipurpose_hall) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F04';
                    $param['total'] = $file->facility->multipurpose_hall_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Gym
                 */
                if ($file->facility->gym) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F05';
                    $param['total'] = $file->facility->gym_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Taman Permainan
                 */
                if ($file->facility->playground) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F06';
                    $param['total'] = $file->facility->playground_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Pondok Pengawal
                 */
                if ($file->facility->guardhouse) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F07';
                    $param['total'] = $file->facility->guardhouse_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Tadika/Taska
                 */
                if ($file->facility->kindergarten) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F08';
                    $param['total'] = $file->facility->kindergarten_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Kawasan Lapang
                 */
                if ($file->facility->open_space) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F09';
                    $param['total'] = $file->facility->open_space_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Lif
                 */
                if ($file->facility->lift) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F10';
                    $param['total'] = $file->facility->lift_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Rumah Sampah
                 */
                if ($file->facility->rubbish_room) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F11';
                    $param['total'] = $file->facility->rubbish_room_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * Berpagar
                 */
                if ($file->facility->gated) {
                    $param['filing_no'] = $file->file_no;
                    $param['facility'] = 'F12';
                    $param['total'] = $file->facility->gated_unit;

                    if (!empty($param)) {
                        $response[] = $this->curl($file, $action, $method, $param);
                    }
                }

                /*
                 * CCTV
                 */

                /*
                 * Parkir Penduduk
                 */

                /*
                 * Parkir Terbuka
                 */

                /*
                 * Parkir Pelawat
                 */

                /*
                 * Parkir OKU
                 */

                /*
                 * Central Gas Storage
                 */

                /*
                 * STP
                 */

                /*
                 * TNB Sub Station
                 */
            }
        }

        return $response;
    }

    public function putSkimFacility($file) {
        $response = '';
        $action = 'skim-facility';
        $method = 'PUT';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, 'POST', $method);
            if ($existing_sync > 0) {
                if (!empty($file->facility->toArray())) {
                    /*
                     * Pejabat Pengurusan
                     */
                    if ($file->facility->management_office) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F01';
                        $param['total'] = $file->facility->management_office_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Kolam Renang
                     */
                    if ($file->facility->swimming_pool) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F02';
                        $param['total'] = $file->facility->swimming_pool_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Surau
                     */
                    if ($file->facility->surau) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F03';
                        $param['total'] = $file->facility->surau_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Dewan Serbaguna
                     */
                    if ($file->facility->multipurpose_hall) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F04';
                        $param['total'] = $file->facility->multipurpose_hall_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Gym
                     */
                    if ($file->facility->gym) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F05';
                        $param['total'] = $file->facility->gym_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Taman Permainan
                     */
                    if ($file->facility->playground) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F06';
                        $param['total'] = $file->facility->playground_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Pondok Pengawal
                     */
                    if ($file->facility->guardhouse) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F07';
                        $param['total'] = $file->facility->guardhouse_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Tadika/Taska
                     */
                    if ($file->facility->kindergarten) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F08';
                        $param['total'] = $file->facility->kindergarten_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Kawasan Lapang
                     */
                    if ($file->facility->open_space) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F09';
                        $param['total'] = $file->facility->open_space_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Lif
                     */
                    if ($file->facility->lift) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F10';
                        $param['total'] = $file->facility->lift_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Rumah Sampah
                     */
                    if ($file->facility->rubbish_room) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F11';
                        $param['total'] = $file->facility->rubbish_room_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Berpagar
                     */
                    if ($file->facility->gated) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F12';
                        $param['total'] = $file->facility->gated_unit;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * CCTV
                     */

                    /*
                     * Parkir Penduduk
                     */

                    /*
                     * Parkir Terbuka
                     */

                    /*
                     * Parkir Pelawat
                     */

                    /*
                     * Parkir OKU
                     */

                    /*
                     * Central Gas Storage
                     */

                    /*
                     * STP
                     */

                    /*
                     * TNB Sub Station
                     */
                }
            }
        }

        return $response;
    }

    public function deleteSkimFacility($file) {
        $response = '';
        $action = 'skim-facility';
        $method = 'DELETE';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, 'POST', $method);
            if ($existing_sync > 0) {
                if (!empty($file->facility->toArray())) {
                    /*
                     * Pejabat Pengurusan
                     */
                    if (!$file->facility->management_office) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F01';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Kolam Renang
                     */
                    if (!$file->facility->swimming_pool) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F02';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Surau
                     */
                    if (!$file->facility->surau) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F03';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Dewan Serbaguna
                     */
                    if (!$file->facility->multipurpose_hall) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F04';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Gym
                     */
                    if (!$file->facility->gym) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F05';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Taman Permainan
                     */
                    if (!$file->facility->playground) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F06';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Pondok Pengawal
                     */
                    if (!$file->facility->guardhouse) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F07';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Tadika/Taska
                     */
                    if (!$file->facility->kindergarten) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F08';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Kawasan Lapang
                     */
                    if (!$file->facility->open_space) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F09';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Lif
                     */
                    if (!$file->facility->lift) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F10';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Rumah Sampah
                     */
                    if (!$file->facility->rubbish_room) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F11';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * Berpagar
                     */
                    if (!$file->facility->gated) {
                        $param['filing_no'] = $file->file_no;
                        $param['facility'] = 'F12';

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    /*
                     * CCTV
                     */

                    /*
                     * Parkir Penduduk
                     */

                    /*
                     * Parkir Terbuka
                     */

                    /*
                     * Parkir Pelawat
                     */

                    /*
                     * Parkir OKU
                     */

                    /*
                     * Central Gas Storage
                     */

                    /*
                     * STP
                     */

                    /*
                     * TNB Sub Station
                     */
                }
            }
        }

        return $response;
    }

    public function postSkimImage($file) {
        $response = '';
        $action = 'skim-image';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if (!empty($file->other->toArray())) {
                    $param['filing_no'] = $file->file_no;
                    $param['reference_key'] = $file->file_no;
                    $param['file_name'] = 'Strata Image';
                    if (!empty($file->other->image_url)) {
                        $image = public_path($file->other->image_url);
                        $param['image'] = new CURLFILE($image);
                    } else {
                        $param['image'] = '';
                    }

                    if (!empty($param) && !empty($param['image'])) {
                        $response = $this->curl($file, $action, $method, $param);
                    }
                }
            }
        }

        return $response;
    }

    public function postSkimMeeting($file) {
        $response = '';
        $action = 'skim-meeting';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if (!empty($file->meetingDocument->toArray())) {
                    foreach ($file->meetingDocument as $meetingDocument) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        if ($meetingDocument->agm) {
                            $param['type'] = 'AGM';
                        } else if ($meetingDocument->egm) {
                            $param['type'] = $this->egm;
                        }
                        if (!empty($meetingDocument->agm_date) && $meetingDocument->agm_date != '0000-00-00') {
                            $param['meeting_date'] = $meetingDocument->agm_date;
                        } else {
                            $param['meeting_date'] = '';
                        }
                        $param['file_name'] = 'Meeting Documents';
                        if (!empty($meetingDocument->minutes_meeting_file_url)) {
                            $image = public_path($meetingDocument->minutes_meeting_file_url);
                            $param['file_minutes'] = new CURLFILE($image);
                        } else {
                            $param['file_minutes'] = '';
                        }
                        if (!empty($meetingDocument->attendance_file_url)) {
                            $image = public_path($meetingDocument->attendance_file_url);
                            $param['file_attendance'] = new CURLFILE($image);
                        } else {
                            $param['file_attendance'] = '';
                        }
                        if (!empty($meetingDocument->eligible_vote_url)) {
                            $image = public_path($meetingDocument->eligible_vote_url);
                            $param['file_voting'] = new CURLFILE($image);
                        } else {
                            $param['file_voting'] = '';
                        }

                        if (!empty($param) && !empty($param['file_minutes'])) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }
                }
            }
        }

        return $response;
    }

    public function postSkimFinancial($file) {
        $response = '';
        $action = 'skim-financial';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if (!empty($file->meetingDocument->toArray())) {
                    foreach ($file->meetingDocument as $meetingDocument) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['date_report'] = date('Y-m-d');
                        $param['file_name'] = 'Financial Report';
                        if (!empty($meetingDocument->audited_financial_file_url)) {
                            $image = public_path($meetingDocument->audited_financial_file_url);
                            $param['file_report'] = new CURLFILE($image);
                        } else {
                            $param['file_report'] = '';
                        }
                        $param['is_audited'] = 'N';
                        $param['date_report_end'] = '';

                        if (!empty($param)) {
                            $response = $this->curl($file, $action, $method, $param);
                        }
                    }
                }
            }
        }

        return $response;
    }

    public function postSkimManagement($file) {
        $response = '';
        $action = 'skim-management';
        $method = 'POST';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, $action, $method);
            if ($existing_sync <= 0) {
                if (!empty($file->management->toArray())) {
                    if ($file->management->is_jmb && $file->managementJMB) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'JMB';
                        $param['management_name'] = (!empty($file->managementJMB->name) ? $file->managementJMB->name : '(Not Available)');
                        $param['establishment_date'] = ((!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00') ? $file->managementJMB->date_formed : date('Y-m-d'));
                        $param['cert_no'] = $file->managementJMB->certificate_no;
                        if (!empty($file->managementJMB->poscode) && !empty($file->managementJMB->state)) {
                            $param['address'] = ($file->managementJMB->address1 ? $file->managementJMB->address1 . ', ' : '') . ($file->managementJMB->address2 ? $file->managementJMB->address2 . ', ' : '') . ($file->managementJMB->address3 ? $file->managementJMB->address3 . ', ' : '');
                        } else {
                            $param['address'] = '';
                        }
                        $param['postcode'] = $file->managementJMB->poscode;
                        $param['state'] = ($file->managementJMB->state ? $file->managementJMB->states->code : '');
                        $param['district'] = ($file->managementJMB->city ? $file->managementJMB->cities->description : '');
                        $param['phone_no'] = $file->managementJMB->phone_no;
                        $param['fax_no'] = $file->managementJMB->fax_no;
                        $param['email'] = $file->managementJMB->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    if ($file->management->is_mc && $file->managementMC) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'MC';
                        $param['management_name'] = (!empty($file->managementMC->name) ? $file->managementMC->name : '(Not Available)');
                        $param['establishment_date'] = ((!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00') ? $file->managementMC->date_formed : date('Y-m-d'));
                        $param['cert_no'] = $file->managementMC->certificate_no;
                        if (!empty($file->managementMC->poscode) && !empty($file->managementMC->state)) {
                            $param['address'] = ($file->managementMC->address1 ? $file->managementMC->address1 . ', ' : '') . ($file->managementMC->address2 ? $file->managementMC->address2 . ', ' : '') . ($file->managementMC->address3 ? $file->managementMC->address3 . ', ' : '');
                        } else {
                            $param['address'] = '';
                        }
                        $param['postcode'] = $file->managementMC->poscode;
                        $param['state'] = ($file->managementMC->state ? $file->managementMC->states->code : '');
                        $param['district'] = ($file->managementMC->city ? $file->managementMC->cities->description : '');
                        $param['phone_no'] = $file->managementMC->phone_no;
                        $param['fax_no'] = $file->managementMC->fax_no;
                        $param['email'] = $file->managementMC->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    if ($file->management->is_agent && $file->managementAgent) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'A';
                        $param['management_name'] = (!empty($file->managementAgent->name) ? $file->managementAgent->name : '(Not Available)');
                        $param['establishment_date'] = ((!empty($file->managementAgent->date_formed) && $file->managementAgent->date_formed != '0000-00-00') ? $file->managementAgent->date_formed : date('Y-m-d'));
                        $param['cert_no'] = $file->managementAgent->certificate_no;
                        if (!empty($file->managementAgent->poscode) && !empty($file->managementAgent->state)) {
                            $param['address'] = ($file->managementAgent->address1 ? $file->managementAgent->address1 . ', ' : '') . ($file->managementAgent->address2 ? $file->managementAgent->address2 . ', ' : '') . ($file->managementAgent->address3 ? $file->managementAgent->address3 . ', ' : '');
                        } else {
                            $param['address'] = '';
                        }
                        $param['postcode'] = $file->managementAgent->poscode;
                        $param['state'] = ($file->managementAgent->state ? $file->managementAgent->states->code : '');
                        $param['district'] = ($file->managementAgent->city ? $file->managementAgent->cities->description : '');
                        $param['phone_no'] = $file->managementAgent->phone_no;
                        $param['fax_no'] = $file->managementAgent->fax_no;
                        $param['email'] = $file->managementAgent->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }
                }
            }
        }

        return $response;
    }

    public function putSkimManagement($file) {
        $response = '';
        $action = 'skim-management';
        $method = 'PUT';
        $param = array();

        if (!empty($file) && !empty($file->file_no)) {
            $existing_sync = $this->existingLog($file, 'POST', $method);
            if ($existing_sync > 0) {
                if (!empty($file->management->toArray())) {
                    if ($file->management->is_jmb && $file->managementJMB) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'JMB';
                        $param['management_name'] = $file->managementJMB->name;
                        $param['establishment_date'] = ($file->managementJMB->date_formed > 0 ? $file->managementJMB->date_formed : '');
                        $param['cert_no'] = $file->managementJMB->certificate_no;
                        $param['address'] = ($file->managementJMB->address1 ? $file->managementJMB->address1 . ', ' : '') . ($file->managementJMB->address2 ? $file->managementJMB->address2 . ', ' : '') . ($file->managementJMB->address3 ? $file->managementJMB->address3 . ', ' : '');
                        $param['postcode'] = $file->managementJMB->poscode;
                        $param['state'] = ($file->managementJMB->state ? $file->managementJMB->states->code : '');
                        $param['district'] = ($file->managementJMB->city ? $file->managementJMB->cities->description : '');
                        $param['phone_no'] = $file->managementJMB->phone_no;
                        $param['fax_no'] = $file->managementJMB->fax_no;
                        $param['email'] = $file->managementJMB->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    if ($file->management->is_mc && $file->managementMC) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'MC';
                        $param['management_name'] = $file->managementMC->name;
                        $param['establishment_date'] = ((!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00') ? $file->managementMC->date_formed : '');
                        $param['cert_no'] = $file->managementMC->certificate_no;
                        $param['address'] = ($file->managementMC->address1 ? $file->managementMC->address1 . ', ' : '') . ($file->managementMC->address2 ? $file->managementMC->address2 . ', ' : '') . ($file->managementMC->address3 ? $file->managementMC->address3 . ', ' : '');
                        $param['postcode'] = $file->managementMC->poscode;
                        $param['state'] = ($file->managementMC->state ? $file->managementMC->states->code : '');
                        $param['district'] = ($file->managementMC->city ? $file->managementMC->cities->description : '');
                        $param['phone_no'] = $file->managementMC->phone_no;
                        $param['fax_no'] = $file->managementMC->fax_no;
                        $param['email'] = $file->managementMC->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }

                    if ($file->management->is_agent && $file->managementAgent) {
                        $param['filing_no'] = $file->file_no;
                        $param['reference_key'] = $file->file_no;
                        $param['management_type'] = 'A';
                        $param['management_name'] = $file->managementAgent->name;
                        $param['establishment_date'] = ($file->managementAgent->date_formed > 0 ? $file->managementAgent->date_formed : '');
                        $param['cert_no'] = $file->managementAgent->certificate_no;
                        $param['address'] = ($file->managementAgent->address1 ? $file->managementAgent->address1 . ', ' : '') . ($file->managementAgent->address2 ? $file->managementAgent->address2 . ', ' : '') . ($file->managementAgent->address3 ? $file->managementAgent->address3 . ', ' : '');
                        $param['postcode'] = $file->managementAgent->poscode;
                        $param['state'] = ($file->managementAgent->state ? $file->managementAgent->states->code : '');
                        $param['district'] = ($file->managementAgent->city ? $file->managementAgent->cities->description : '');
                        $param['phone_no'] = $file->managementAgent->phone_no;
                        $param['fax_no'] = $file->managementAgent->fax_no;
                        $param['email'] = $file->managementAgent->email;

                        if (!empty($param)) {
                            $response[] = $this->curl($file, $action, $method, $param);
                        }
                    }
                }
            }
        }

        return $response;
    }

    public function curl($file, $action, $method, $param) {
        $response = '';

        if ($this->debug) {
            $response = json_encode($param);
        } else {
            $url = $this->api_endpoint . $action;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $param,
                CURLOPT_SAFE_UPLOAD => false,
                CURLOPT_HTTPHEADER => array(
                    'token: ' . $this->token_key
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            if ($response) {
                $this->syncLog($file, $action, $method, $param, json_decode($response, true));
            }
        }

        return json_decode($response, true);
    }

    public function syncLog($file, $action, $method, $param, $response) {
        $log = new SyncLog();
        $log->file_id = $file->id;
        $log->file_no = $file->file_no;
        $log->api_endpoint = $this->api_endpoint;
        $log->action = $action;
        $log->method = $method;
        $log->param = json_encode($param);
        $log->response = json_encode($response);
        $log->status = (isset($response['status']) ? $response['status'] : 500);
        $success = $log->save();

        if ($success) {
            return true;
        }

        return false;
    }

}
