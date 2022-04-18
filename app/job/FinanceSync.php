<?php

namespace Job;

use Company;
use Exception;
use Files;
use Finance;
use FinanceAdmin;
use FinanceCheck;
use FinanceContract;
use FinanceIncome;
use FinanceRepair;
use FinanceReport;
use FinanceReportExtra;
use FinanceReportPerbelanjaan;
use FinanceStaff;
use FinanceSummary;
use FinanceUtility;
use FinanceVandal;
use Helper\KCurl;

class FinanceSync
{
    public $api_domain;

    public function __construct()
    {
        $this->api_domain = 'https://test.odesi.tech/api/v4/';
    }

    public function fire($job, $data)
    {
        // Process the job...
        if (!empty($data)) {
            $council_code = $data['council_code'];
            $file_no = $data['file_no'];
            $finance = $data['finance'];

            $council = Company::where('short_name', $council_code)->first();
            if ($council && !empty($finance)) {
                $file = Files::where('company_id', $council->id)
                    ->where('file_no', $file_no)
                    ->where('is_deleted', 0)
                    ->first();

                if ($file) {
                    $exist_finance = Finance::where('file_id', $file->id)
                        ->where('company_id', $council->id)
                        ->where('year', $finance['year'])
                        ->where('month', $finance['month'])
                        ->where('is_deleted', 0)
                        ->first();

                    if (!$exist_finance) {
                        $new_finance = new Finance();
                        $new_finance->file_id = $file->id;
                        $new_finance->company_id = $council->id;
                        $new_finance->month = $finance['month'];
                        $new_finance->year = $finance['year'];
                        $new_finance->is_active = $finance['is_active'];
                        $new_finance->is_deleted = $finance['is_deleted'];
                        $new_finance->from_api = $finance['from_api'];
                        $success = $new_finance->save();

                        if ($success) {
                            $exist_finance = $new_finance;
                        }
                    }

                    if (!empty($exist_finance)) {
                        // create Check
                        $path_check = 'financeCheck?finance_id=' . $finance['id'];
                        $finances_checks = json_decode($this->curl($path_check));

                        if (!empty($finances_checks)) {
                            foreach ($finances_checks as $finances_check) {
                                $new_check = FinanceCheck::where('finance_file_id', $exist_finance->id)
                                    ->where('name', $finances_check->name)
                                    ->first();

                                if (!$new_check) {
                                    $new_check = new FinanceCheck();
                                    $new_check->finance_file_id = $exist_finance->id;
                                    $new_check->name = $finances_check->name;
                                }
                                $new_check->date = ($finances_check->date != '0000-00-00' ? $finances_check->date : null);
                                $new_check->position = $finances_check->position;
                                $new_check->remarks = $finances_check->remarks;
                                $new_check->is_active = $finances_check->is_active;
                                $new_check->save();
                            }
                        }

                        // create summary
                        $path_summary = 'financeSummary?finance_id=' . $finance['id'];
                        $finances_summaries = json_decode($this->curl($path_summary));

                        if (!empty($finances_summaries)) {
                            foreach ($finances_summaries as $finances_summary) {
                                $new_summary = FinanceSummary::where('finance_file_id', $exist_finance->id)
                                    ->where('summary_key', $finances_summary->summary_key)
                                    ->first();

                                if (!$new_summary) {
                                    $new_summary = new FinanceSummary();
                                    $new_summary->finance_file_id = $exist_finance->id;
                                    $new_summary->summary_key = $finances_summary->summary_key;
                                }
                                $new_summary->name = $finances_summary->name;
                                $new_summary->amount = $finances_summary->amount;
                                $new_summary->sort_no = $finances_summary->sort_no;
                                $new_summary->save();
                            }
                        }

                        // create report
                        $path_report = 'financeReport?finance_id=' . $finance['id'];
                        $finances_reports = json_decode($this->curl($path_report));

                        if (!empty($finances_reports)) {
                            foreach ($finances_reports as $finances_report) {
                                $new_report = FinanceReport::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_report->type)
                                    ->first();

                                if (!$new_report) {
                                    $new_report = new FinanceReport();
                                    $new_report->finance_file_id = $exist_finance->id;
                                    $new_report->type = $finances_report->type;
                                }
                                $new_report->fee_sebulan = $finances_report->fee_sebulan;
                                $new_report->unit = $finances_report->unit;
                                $new_report->fee_semasa = $finances_report->fee_semasa;
                                $new_report->no_akaun = $finances_report->no_akaun;
                                $new_report->nama_bank = $finances_report->nama_bank;
                                $new_report->baki_bank_akhir = $finances_report->baki_bank_akhir;
                                $new_report->baki_bank_awal = $finances_report->baki_bank_awal;
                                $new_report->save();
                            }
                        }

                        // create report extra
                        $path_report_extra = 'financeReportExtra?finance_id=' . $finance['id'];
                        $finances_report_extras = json_decode($this->curl($path_report_extra));

                        if (!empty($finances_report_extras)) {
                            foreach ($finances_report_extras as $finances_report_extra) {
                                $new_report_extra = FinanceReportExtra::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_report_extra->type)
                                    ->first();

                                if (!$new_report_extra) {
                                    $new_report_extra = new FinanceReportExtra();
                                    $new_report_extra->finance_file_id = $exist_finance->id;
                                    $new_report_extra->type = $finances_report_extra->type;
                                }
                                $new_report_extra->fee_sebulan = $finances_report_extra->fee_sebulan;
                                $new_report_extra->unit = $finances_report_extra->unit;
                                $new_report_extra->fee_semasa = $finances_report_extra->fee_semasa;
                                $new_report_extra->save();
                            }
                        }

                        // create report perbelanjaan
                        $path_report_perbelanjaan = 'financeReportPerbelanjaan?finance_id=' . $finance['id'];
                        $finances_report_perbelanjaans = json_decode($this->curl($path_report_perbelanjaan));

                        if (!empty($finances_report_perbelanjaans)) {
                            foreach ($finances_report_perbelanjaans as $finances_report_perbelanjaan) {
                                $new_report_perbelanjaan = FinanceReportPerbelanjaan::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_report_perbelanjaan->type)
                                    ->where('report_key', $finances_report_perbelanjaan->report_key)
                                    ->first();

                                if (!$new_report_perbelanjaan) {
                                    $new_report_perbelanjaan = new FinanceReportPerbelanjaan();
                                    $new_report_perbelanjaan->finance_file_id = $exist_finance->id;
                                    $new_report_perbelanjaan->type = $finances_report_perbelanjaan->type;
                                    $new_report_perbelanjaan->report_key = $finances_report_perbelanjaan->report_key;
                                }
                                $new_report_perbelanjaan->name = $finances_report_perbelanjaan->name;
                                $new_report_perbelanjaan->amount = $finances_report_perbelanjaan->amount;
                                $new_report_perbelanjaan->sort_no = $finances_report_perbelanjaan->sort_no;
                                $new_report_perbelanjaan->is_custom = $finances_report_perbelanjaan->is_custom;
                                $new_report_perbelanjaan->save();
                            }
                        }

                        // create income
                        $path_income = 'financeIncome?finance_id=' . $finance['id'];
                        $finances_incomes = json_decode($this->curl($path_income));

                        if (!empty($finances_incomes)) {
                            foreach ($finances_incomes as $finances_income) {
                                $new_income = FinanceIncome::where('finance_file_id', $exist_finance->id)
                                    ->where('name', $finances_income->name)
                                    ->first();

                                if (!$new_income) {
                                    $new_income = new FinanceIncome();
                                    $new_income->finance_file_id = $exist_finance->id;
                                    $new_income->name = $finances_income->name;
                                }
                                $new_income->tunggakan = $finances_income->tunggakan;
                                $new_income->semasa = $finances_income->semasa;
                                $new_income->hadapan = $finances_income->hadapan;
                                $new_income->sort_no = $finances_income->sort_no;
                                $new_income->is_custom = $finances_income->is_custom;
                                $new_income->save();
                            }
                        }

                        // create utility
                        $path_utility = 'financeUtility?finance_id=' . $finance['id'];
                        $finances_utilities = json_decode($this->curl($path_utility));

                        if (!empty($finances_utilities)) {
                            foreach ($finances_utilities as $finances_utility) {
                                $new_utility = FinanceUtility::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_utility->type)
                                    ->where('name', $finances_utility->name)
                                    ->first();

                                if (!$new_utility) {
                                    $new_utility = new FinanceUtility();
                                    $new_utility->finance_file_id = $exist_finance->id;
                                    $new_utility->type = $finances_utility->type;
                                    $new_utility->name = $finances_utility->name;
                                }
                                $new_utility->tunggakan = $finances_utility->tunggakan;
                                $new_utility->semasa = $finances_utility->semasa;
                                $new_utility->hadapan = $finances_utility->hadapan;
                                $new_utility->tertunggak = $finances_utility->tertunggak;
                                $new_utility->sort_no = $finances_utility->sort_no;
                                $new_utility->is_custom = $finances_utility->is_custom;
                                $new_utility->save();
                            }
                        }

                        // create contract
                        $path_contract = 'financeContract?finance_id=' . $finance['id'];
                        $finances_contracts = json_decode($this->curl($path_contract));

                        if (!empty($finances_contracts)) {
                            foreach ($finances_contracts as $finances_contract) {
                                $new_contract = FinanceContract::where('finance_file_id', $exist_finance->id)
                                    ->where('name', $finances_contract->name)
                                    ->first();

                                if (!$new_contract) {
                                    $new_contract = new FinanceContract();
                                    $new_contract->finance_file_id = $exist_finance->id;
                                    $new_contract->name = $finances_contract->name;
                                }
                                $new_contract->tunggakan = $finances_contract->tunggakan;
                                $new_contract->semasa = $finances_contract->semasa;
                                $new_contract->hadapan = $finances_contract->hadapan;
                                $new_contract->tertunggak = $finances_contract->tertunggak;
                                $new_contract->sort_no = $finances_contract->sort_no;
                                $new_contract->is_custom = $finances_contract->is_custom;
                                $new_contract->save();
                            }
                        }

                        // create repair
                        $path_repair = 'financeRepair?finance_id=' . $finance['id'];
                        $finances_repairs = json_decode($this->curl($path_repair));

                        if (!empty($finances_repairs)) {
                            foreach ($finances_repairs as $finances_repair) {
                                $new_repair = FinanceRepair::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_repair->type)
                                    ->where('name', $finances_repair->name)
                                    ->first();

                                if (!$new_repair) {
                                    $new_repair = new FinanceRepair();
                                    $new_repair->finance_file_id = $exist_finance->id;
                                    $new_repair->type = $finances_repair->type;
                                    $new_repair->name = $finances_repair->name;
                                }
                                $new_repair->tunggakan = $finances_repair->tunggakan;
                                $new_repair->semasa = $finances_repair->semasa;
                                $new_repair->hadapan = $finances_repair->hadapan;
                                $new_repair->tertunggak = $finances_repair->tertunggak;
                                $new_repair->sort_no = $finances_repair->sort_no;
                                $new_repair->is_custom = $finances_repair->is_custom;
                                $new_repair->save();
                            }
                        }

                        // create vandalisme
                        $path_vandalisme = 'financeVandalisme?finance_id=' . $finance['id'];
                        $finances_vandalismes = json_decode($this->curl($path_vandalisme));

                        if (!empty($finances_vandalismes)) {
                            foreach ($finances_vandalismes as $finances_vandalisme) {
                                $new_vandalisme = FinanceVandal::where('finance_file_id', $exist_finance->id)
                                    ->where('type', $finances_vandalisme->type)
                                    ->where('name', $finances_vandalisme->name)
                                    ->first();

                                if (!$new_vandalisme) {
                                    $new_vandalisme = new FinanceVandal();
                                    $new_vandalisme->finance_file_id = $exist_finance->id;
                                    $new_vandalisme->type = $finances_vandalisme->type;
                                    $new_vandalisme->name = $finances_vandalisme->name;
                                }
                                $new_vandalisme->tunggakan = $finances_vandalisme->tunggakan;
                                $new_vandalisme->semasa = $finances_vandalisme->semasa;
                                $new_vandalisme->hadapan = $finances_vandalisme->hadapan;
                                $new_vandalisme->tertunggak = $finances_vandalisme->tertunggak;
                                $new_vandalisme->sort_no = $finances_vandalisme->sort_no;
                                $new_vandalisme->is_custom = $finances_vandalisme->is_custom;
                                $new_vandalisme->save();
                            }
                        }

                        // create staff
                        $path_staff = 'financeStaff?finance_id=' . $finance['id'];
                        $finances_staffs = json_decode($this->curl($path_staff));

                        if (!empty($finances_staffs)) {
                            foreach ($finances_staffs as $finances_staff) {
                                $new_staff = FinanceStaff::where('finance_file_id', $exist_finance->id)
                                    ->where('name', $finances_staff->name)
                                    ->first();

                                if (!$new_staff) {
                                    $new_staff = new FinanceStaff();
                                    $new_staff->finance_file_id = $exist_finance->id;
                                    $new_staff->name = $finances_staff->name;
                                }
                                $new_staff->gaji_per_orang = $finances_staff->gaji_per_orang;
                                $new_staff->bil_pekerja = $finances_staff->bil_pekerja;
                                $new_staff->tunggakan = $finances_staff->tunggakan;
                                $new_staff->semasa = $finances_staff->semasa;
                                $new_staff->hadapan = $finances_staff->hadapan;
                                $new_staff->tertunggak = $finances_staff->tertunggak;
                                $new_staff->sort_no = $finances_staff->sort_no;
                                $new_staff->is_custom = $finances_staff->is_custom;
                                $new_staff->save();
                            }
                        }

                        // create admin
                        $path_admin = 'financeAdmin?finance_id=' . $finance['id'];
                        $finances_admins = json_decode($this->curl($path_admin));

                        if (!empty($finances_admins)) {
                            foreach ($finances_admins as $finances_admin) {
                                $new_admin = FinanceAdmin::where('finance_file_id', $exist_finance->id)
                                    ->where('name', $finances_admin->name)
                                    ->first();

                                if (!$new_admin) {
                                    $new_admin = new FinanceAdmin();
                                    $new_admin->finance_file_id = $exist_finance->id;
                                    $new_admin->name = $finances_admin->name;
                                }
                                $new_admin->tunggakan = $finances_admin->tunggakan;
                                $new_admin->semasa = $finances_admin->semasa;
                                $new_admin->hadapan = $finances_admin->hadapan;
                                $new_admin->tertunggak = $finances_admin->tertunggak;
                                $new_admin->sort_no = $finances_admin->sort_no;
                                $new_admin->is_custom = $finances_admin->is_custom;
                                $new_admin->save();
                            }
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
        try {
            // curl to get data
            $url = $this->api_domain . $path;
            $response = json_decode((string) ((new KCurl())->requestGET($this->getHeader(), $url)));

            if (empty($response->success) == false && $response->success == true) {
                $items = $response->data;

                return json_encode($items);
            }

            return false;
        } catch (Exception $e) {
            throw ($e);
        }
    }
}
