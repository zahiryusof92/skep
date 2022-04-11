<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubModuleTableSeeder extends Seeder {

    public function run()
    {
        $table = SubModule::where('name_en', 'Liquidator')->first();

        if(!$table) {
            $module = Module::where('name_en', 'Master Setup')->first();
            if($module) {
                SubModule::firstOrCreate([
                    'module_id' => $module->id,
                    'name_en' => 'Liquidator',
                    'name_my' => 'Liquidator',
                    'sort_no' => 21
                ]);
            }
        }
        
        $epks = Module::where('name_en', 'EPKS')->first();
        if(empty($epks)) {
            $epks = new Module;
            $epks->name_en = "EPKS";
            $epks->name_my = "EPKS";
            $epks->save();
        }
        if($epks) {
            SubModule::firstOrCreate([
                'module_id' => $epks->id,
                'name_en' => 'EPKS',
                'name_my' => 'EPKS',
                'sort_no' => 1
            ]);
        }
        $report = Module::where('name_en', 'Reporting')->first();
        if($report) {
            SubModule::firstOrCreate([
                'module_id' => $report->id,
                'name_en' => 'EPKS',
                'name_my' => 'EPKS',
                'sort_no' => 17
            ]);
        }
    }

}