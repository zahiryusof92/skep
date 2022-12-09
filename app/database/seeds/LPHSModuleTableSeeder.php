<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LPHSModuleTableSeeder extends Seeder
{

    public function run()
    {
        $postponed_agm = Module::where('name_en', 'Postponed AGM')->first();
        if (empty($postponed_agm)) {
            $postponed_agm = new Module;
            $postponed_agm->name_en = "Postponed AGM";
            $postponed_agm->name_my = "Postponed AGM";
            $postponed_agm->save();
        }

        if ($postponed_agm) {
            SubModule::firstOrCreate([
                'module_id' => $postponed_agm->id,
                'name_en' => 'Postponed AGM',
                'name_my' => 'Postponed AGM',
                'sort_no' => 1
            ]);
        }

        $dlp = Module::where('name_en', 'Defect Liability Period')->first();
        if (empty($dlp)) {
            $dlp = new Module;
            $dlp->name_en = "Defect Liability Period";
            $dlp->name_my = "Defect Liability Period";
            $dlp->save();
        }

        if ($dlp) {
            SubModule::firstOrCreate([
                'module_id' => $dlp->id,
                'name_en' => 'Defect Liability Period',
                'name_my' => 'Defect Liability Period',
                'sort_no' => 1
            ]);
        }

        $ledger = Module::where('name_en', 'Ledger')->first();
        if (empty($ledger)) {
            $ledger = new Module;
            $ledger->name_en = "Ledger";
            $ledger->name_my = "Ledger";
            $ledger->save();
        }

        if ($ledger) {
            SubModule::firstOrCreate([
                'module_id' => $ledger->id,
                'name_en' => 'Ledger',
                'name_my' => 'Ledger',
                'sort_no' => 1
            ]);
        }

        $master_module = Module::where('name_en', 'Master Setup')->first();
        if ($master_module) {
            SubModule::firstOrCreate([
                'module_id' => $master_module->id,
                'name_en' => 'Postponed AGM Reason',
                'name_my' => 'Postponed AGM Reason',
                'sort_no' => 22
            ]);
        }
    }
}
