<?php

use Illuminate\Database\Seeder;

class ComplaintModuleTableSeeder extends Seeder
{
    public function run()
    {
        $complaint = Module::where('name_en', 'Complaint')->first();
        if (empty($complaint)) {
            $complaint = new Module;
            $complaint->name_en = 'Complaint';
            $complaint->name_my = 'Aduan';
            $complaint->save();
        }

        if ($complaint) {
            SubModule::firstOrCreate([
                'module_id' => $complaint->id,
                'name_en' => 'Complaint',
                'name_my' => 'Aduan',
                'sort_no' => 1
            ]);
        }

        $master_module = Module::where('name_en', 'Master Setup')->first();
        if ($master_module) {
            SubModule::firstOrCreate([
                'module_id' => $master_module->id,
                'name_en' => 'Complaint Category',
                'name_my' => 'Kategori Aduan',
                'sort_no' => 22
            ]);
        }

        echo "Complaint modules seeded.\n";
    }
}
