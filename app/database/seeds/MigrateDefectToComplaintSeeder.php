<?php

use Illuminate\Database\Seeder;

class MigrateDefectToComplaintSeeder extends Seeder
{

    public function run()
    {
        $data = [];

        $categories = [
            'meeting' => 'Mesyuarat dan Pemilihan',
            'management' => 'Pentadbiran & Pengurusan',
            'maintenance' => 'Penyenggaraan dan Kerosakan',
            'finance' => 'Kewangan',
            'advisor' => 'Khidmat Nasihat',
            'cob' => 'Penguatkuasaan Pesuruhjaya Bangunan (COB)',
            'others' => 'Lain-lain',
        ];

        $types = [
            '1' => 'Lif',
            '2' => 'Sampah',
            '3' => 'Kebocoran Di Dalam Unit',
            '4' => 'Pembentungan',
            '5' => 'Pembentungan',
            '6' => 'Pendawaian & Eletrik',
            '7' => 'Gutter & Rain Water Down Part (RWDP)',
            '8' => 'Kacau Ganggu',
            '9' => 'Lanskap',
            '10' => 'Tangki Air',
            '11' => 'Kebocoran Antara Tingkat (Interfloor Leakage)',
            '12' => 'Lain-lain', // other
            '13' => 'Waterproofing',
            '14' => 'Keselamatan & Kesihatan',
            '15' => 'Aduan Berulang', // other
            '16' => 'Mesyuarat Agung Tahunan (AGM)',
            '17' => 'Laporan Kewangan (Bulanan / Tahunan)',
            '18' => 'Lain-lain', // other
            '19' => 'Pengubahsuaian Dalaman Unit',
            '20' => 'Pejabat Pengurusan (MO)',
            '21' => 'Louvers',
            '22' => 'Fasad Bangunan',
        ];

        $defects = Defect::where('is_deleted', false)->get();
        if ($defects) {
            foreach ($defects as $defect) {
                $typeName = $types[$defect->defect_category_id];

                $type = ComplaintType::where('name', $typeName)->first();
                if ($type) {
                    $categoryId = $type->complaint_category_id;
                    $typeId = $type->id;
                } else {
                    $categoryName = $categories[$defect->complaint_type];
                    $category = ComplaintCategory::where('name', $categoryName)->first();
                    $categoryId = ($category ? $category->id : null);
                    $typeId = null;
                }

                $data[$defect->id] = [
                    'file_id' => $defect->file_id,
                    'complaint_category_id' => $categoryId,
                    'complaint_type_id' => $typeId,
                    'name' => $defect->name,
                    'description' => $defect->description,
                    'attachment_url' => $defect->attachment_url,
                    'letter_ref_no' => $defect->letter_ref_no,
                    'date_received' => $defect->date_received,
                    'scheme_address' => $defect->scheme_address,
                    'scheme_zone' => $defect->scheme_zone,
                    'scheme_receiver' => $defect->scheme_receiver,
                    'complainant_phone_no' => $defect->complainant_phone_no,
                    'complainant_email' => $defect->complainant_email,
                    'complainant_ic_no' => $defect->complainant_ic_no,
                    'complainant_type' => $defect->complainant_type,
                    'complainant_type_others' => $defect->complainant_type_others,
                    'complaint_category' => $defect->complaint_category,
                    'complaint_complication' => $defect->complaint_complication,
                    'complaint_status' => $defect->complaint_status,
                    'action_duration' => $defect->action_duration,
                    'officer_review' => $defect->officer_review,
                    'status' => $defect->status,
                    'created_at' => $defect->created_at,
                    'updated_at' => $defect->updated_at,
                ];
            }
        }

        if (!empty($data)) {
            // \Log::debug('Data: ' . print_r($data, true));

            foreach ($data as $id => $item) {
                Complaint::insert($item);
            }
        }        

        echo "Migrate Defect to Complaint seeded.\n";
    }
}
