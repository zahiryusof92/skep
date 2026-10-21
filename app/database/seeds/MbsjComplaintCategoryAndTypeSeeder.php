<?php

use Illuminate\Database\Seeder;

class MbsjComplaintCategoryAndTypeSeeder extends Seeder
{
    /**
     * Seed the MBSJ complaint categories and types.
     *
     * Safe to run multiple times: rows are matched by name + cob and updated.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Mesyuarat dan Pemilihan',
                'description' => '(Semua aduan berkaitan mesyuarat yang melibatkan Badan termasuk berkaitan proses penubuhan Badan)',
                'color_code' => '#008000',
                'sort_no' => 1,
                'types' => [
                    ['name' => 'Mesyuarat Agung Tahunan (AGM)', 'color_code' => '#008000', 'sort_no' => 1],
                    ['name' => 'Mesyuarat Agung Luar Biasa (EGM)', 'color_code' => '#008000', 'sort_no' => 2],
                    ['name' => 'Mesyuarat Agung Luar Biasa (FAGM)', 'color_code' => '#008000', 'sort_no' => 3],
                    ['name' => 'Mesyuarat AJK', 'color_code' => '#008000', 'sort_no' => 4],
                ],
            ],
            [
                'name' => 'Pentadbiran dan Pengurusan',
                'description' => '(Semua aduan berkaitan pentadbiran dan pengurusan Pemaju/ Badan/ Ejen termasuk tanggungjawab, kerjasama, pertikaian)',
                'color_code' => '#0000FF',
                'sort_no' => 2,
                'types' => [
                    ['name' => 'Pengurusan', 'color_code' => '#0000FF', 'sort_no' => 1],
                    ['name' => 'Pentadbiran', 'color_code' => '#0000FF', 'sort_no' => 2],
                    ['name' => 'Ejen Pengurusan', 'color_code' => '#0000FF', 'sort_no' => 3],
                    ['name' => 'Pejabat Pengurusan', 'color_code' => '#0000FF', 'sort_no' => 4],
                    ['name' => 'Keselamatan & Kesihatan', 'color_code' => '#0000FF', 'sort_no' => 5],
                    ['name' => 'Elaun AJK', 'color_code' => '#0000FF', 'sort_no' => 6],
                ],
            ],
            [
                'name' => 'Penyenggaraan dan Kerosakan',
                'description' => '(Semua aduan berkaitan penyenggaraan dan kerosakan bangunan dan harta bersama)',
                'color_code' => '#FFA500',
                'sort_no' => 3,
                'types' => [
                    ['name' => 'Louvers', 'color_code' => '#FFA500', 'sort_no' => 1],
                    ['name' => 'Lif', 'color_code' => '#FFA500', 'sort_no' => 2],
                    ['name' => 'Buang Sampah', 'color_code' => '#FFA500', 'sort_no' => 3],
                    ['name' => 'Kebocoran Harta Bersama', 'color_code' => '#FFA500', 'sort_no' => 4],
                    ['name' => 'Kebocoran Antara Tingkat (Interfloor Leakage)', 'color_code' => '#FFA500', 'sort_no' => 5],
                    ['name' => 'Kerosakan Kawasan Harta Bersama', 'color_code' => '#FFA500', 'sort_no' => 6],
                    ['name' => 'Kebocoran Bumbung', 'color_code' => '#FFA500', 'sort_no' => 7],
                    ['name' => 'Pembentungan', 'color_code' => '#FFA500', 'sort_no' => 8],
                    ['name' => 'Pendawaian & Elektrik', 'color_code' => '#FFA500', 'sort_no' => 9],
                    ['name' => 'Gutter & Rain Water Down Part (RWDP)', 'color_code' => '#FFA500', 'sort_no' => 10],
                    ['name' => 'Lanskap', 'color_code' => '#FFA500', 'sort_no' => 11],
                    ['name' => 'Tangki Air', 'color_code' => '#FFA500', 'sort_no' => 12],
                    ['name' => 'Waterproofing', 'color_code' => '#FFA500', 'sort_no' => 13],
                    ['name' => 'Fasad Bangunan', 'color_code' => '#FFA500', 'sort_no' => 14],
                ],
            ],
            [
                'name' => 'Isu Kewangan',
                'description' => '(Semua aduan berkaitan kewangan termasuk Caj Penyenggaraan, Akaun Penyenggaraan Bangunan, Kumpulan Wang Penyenggaraan Bangunan, Kumpulan Wang Penjelas)',
                'color_code' => '#800080',
                'sort_no' => 4,
                'types' => [
                    ['name' => 'Akaun MF / SF', 'color_code' => '#800080', 'sort_no' => 1],
                    ['name' => 'Audit Akaun', 'color_code' => '#800080', 'sort_no' => 2],
                    ['name' => 'Laporan Bulanan', 'color_code' => '#800080', 'sort_no' => 3],
                    ['name' => 'Perolehan', 'color_code' => '#800080', 'sort_no' => 4],
                    ['name' => 'Kontrak', 'color_code' => '#800080', 'sort_no' => 5],
                ],
            ],
            [
                'name' => 'Khidmat Nasihat',
                'description' => '(Semua khidmat nasihat dan pandangan yang diberi kepada pengadu)',
                'color_code' => '#FFC0CB',
                'sort_no' => 5,
                'types' => [
                    ['name' => 'Kaedah Perolehan', 'color_code' => '#FFC0CB', 'sort_no' => 1],
                    ['name' => 'Akta Pengurusan Strata 2013 [Akta 757]', 'color_code' => '#FFC0CB', 'sort_no' => 2],
                    ['name' => 'Peraturan-Peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015', 'color_code' => '#FFC0CB', 'sort_no' => 3],
                ],
            ],
            [
                'name' => 'Penguatkuasaan COB',
                'description' => '(Semua aduan berkaitan penguatkuasaan COB dan Akta)',
                'color_code' => '#FF7518',
                'sort_no' => 6,
                'types' => [
                    ['name' => 'Ingkar Award', 'color_code' => '#FF7518', 'sort_no' => 1],
                    ['name' => 'Kesalahan dalam Akta 757', 'color_code' => '#FF7518', 'sort_no' => 2],
                    ['name' => 'Sitaan Harta Alih', 'color_code' => '#FF7518', 'sort_no' => 3],
                ],
            ],
            [
                'name' => 'Lain-lain',
                'description' => '(Semua aduan berkaitan haiwan liar, bangunan, kacau ganggu, lalu lintas, kebersihan, bencana alam/pokok, perniagaan)',
                'color_code' => '#FFFFFF',
                'sort_no' => 7,
                'types' => [
                    ['name' => 'Haiwan Liar', 'color_code' => '#FFFFFF', 'sort_no' => 1],
                    ['name' => 'Bangunan', 'color_code' => '#FFFFFF', 'sort_no' => 2],
                    ['name' => 'Kacau Ganggu', 'color_code' => '#FFFFFF', 'sort_no' => 3],
                    ['name' => 'Lalu Lintas', 'color_code' => '#FFFFFF', 'sort_no' => 4],
                    ['name' => 'Kebersihan', 'color_code' => '#FFFFFF', 'sort_no' => 5],
                    ['name' => 'Bencana Alam / Pokok', 'color_code' => '#FFFFFF', 'sort_no' => 6],
                    ['name' => 'Perniagaan', 'color_code' => '#FFFFFF', 'sort_no' => 7],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            $types = isset($cat['types']) ? $cat['types'] : [];
            unset($cat['types']);

            $cat['cob'] = 'MBSJ';
            $cat['is_active'] = true;

            $category = ComplaintCategory::withTrashed()
                ->where('name', $cat['name'])
                ->where('cob', 'MBSJ')
                ->first();

            if (!$category) {
                $category = new ComplaintCategory;
            }

            $category->fill($cat);
            $category->deleted_at = null;
            $category->save();

            foreach ($types as $type) {
                $type['cob'] = 'MBSJ';
                $type['is_active'] = true;

                $model = ComplaintType::withTrashed()
                    ->where('complaint_category_id', $category->id)
                    ->where('name', $type['name'])
                    ->where('cob', 'MBSJ')
                    ->first();

                if (!$model) {
                    $model = new ComplaintType;
                }

                $model->fill($type);
                $model->complaint_category_id = $category->id;
                $model->deleted_at = null;
                $model->save();
            }
        }

        echo "MBSJ ComplaintCategory & ComplaintType seeded.\n";
    }
}
