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
                $table = new SubModule;
                $table->module_id = $module->id;
                $table->name_en = 'Liquidator';
                $table->name_my = 'Liquidator';
                $table->sort_no = 21;
                $table->save();
            }
        }

    }

}