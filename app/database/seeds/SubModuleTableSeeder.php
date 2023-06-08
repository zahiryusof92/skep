<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubModuleTableSeeder extends Seeder
{

    public function run()
    {
        $cob_module = Module::where('name_en', 'COB Maintenance')->first();
        if ($cob_module) {
            SubModule::firstOrCreate([
                'module_id' => $cob_module->id,
                'name_en' => 'File Movement',
                'name_my' => 'Pergerakan Fail',
                'sort_no' => 10
            ]);
            SubModule::firstOrCreate([
                'module_id' => $cob_module->id,
                'name_en' => 'Audit Account',
                'name_my' => 'Akaun Audit',
                'sort_no' => 11
            ]);
        }

        $master_module = Module::where('name_en', 'Master Setup')->first();
        if ($master_module) {
            SubModule::firstOrCreate([
                'module_id' => $master_module->id,
                'name_en' => 'Liquidator',
                'name_my' => 'Liquidator',
                'sort_no' => 21
            ]);
        }

        $epks = Module::where('name_en', 'EPKS')->first();
        if (empty($epks)) {
            $epks = new Module;
            $epks->name_en = "EPKS";
            $epks->name_my = "EPKS";
            $epks->save();
        }
        if ($epks) {
            SubModule::firstOrCreate([
                'module_id' => $epks->id,
                'name_en' => 'EPKS',
                'name_my' => 'EPKS',
                'sort_no' => 1
            ]);
        }

        $report = Module::where('name_en', 'Reporting')->first();
        if ($report) {
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'EPKS',
                'name_my' => 'EPKS',
                'sort_no' => 17
            ]);
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'Report Generator',
                'name_my' => 'Report Generator',
                'sort_no' => 18
            ]);
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'Statistics Report',
                'name_my' => 'Laporan Statistik',
                'sort_no' => 20
            ]);
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'Email Log',
                'name_my' => 'Log E-mel',
                'sort_no' => 21
            ]);
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'Notification',
                'name_my' => 'Notifikasi',
                'sort_no' => 22
            ]);
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'Movement of File',
                'name_my' => 'Pergerakan Fail',
                'sort_no' => 23
            ]);
        }

        $cob_letter = Module::where('name_en', 'COB Letter')->first();
        if (empty($cob_letter)) {
            $cob_letter = new Module;
            $cob_letter->name_en = "COB Letter";
            $cob_letter->name_my = "Surat COB";
            $cob_letter->save();
        }
        if ($cob_letter) {
            SubModule::firstOrCreate([
                'module_id' => $cob_letter->id,
                'name_en' => 'COB Letter',
                'name_my' => 'Surat COB',
                'sort_no' => 1
            ]);
        }

        $api_client = Module::where('name_en', 'API Client')->first();
        if (empty($api_client)) {
            $api_client = new Module;
            $api_client->name_en = "API Client";
            $api_client->name_my = "Pelanggan API";
            $api_client->save();
        }
        if ($api_client) {
            SubModule::firstOrCreate([
                'module_id' => $api_client->id,
                'name_en' => 'API Client',
                'name_my' => 'Pelanggan API',
                'sort_no' => 1
            ]);
            SubModule::firstOrCreate([
                'module_id' => $api_client->id,
                'name_en' => 'API Building',
                'name_my' => 'Bangunan API',
                'sort_no' => 1
            ]);
        }
    }
}
