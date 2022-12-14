<?php

use Illuminate\Database\Seeder;

class SubModuleTableSeeder extends Seeder
{
    public function run()
    {
        $module = Module::where('name_en', 'e-Service')->first();
        if (empty($module)) {
            $module = new Module;
            $module->name_en = "e-Service";
            $module->name_my = "e-Perkhimatan";
            $module->save();
        }

        if ($module) {
            SubModule::firstOrCreate([
                'module_id' => $module->id,
                'name_en' => 'e-Service',
                'name_my' => 'e-Perkhimatan',
                'sort_no' => 1
            ]);
        }
    }
}
