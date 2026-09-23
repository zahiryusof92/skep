<?php

use Illuminate\Database\Seeder;

class ComplaintCategoryAndTypeSeeder extends Seeder
{

    public function run()
    {
        $categories = [
            [
                'name' => 'Mesyuarat dan Pemilihan',
                'description' => '(Semua aduan berkaitan mesyuarat dan pemilihan yang melibatkan Badan Pengurusan Bersama / Perbadanan Pengurusan (JMB / MC) termasuk berkaitan proses penubuhan JMB / MC)',
                'color_code' => '#008000',
                'sort_no' => 1,
                'types' => [
                    [
                        'name' => 'Mesyuarat Agung Tahunan (AGM)',
                        'color_code' => '#008000',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Mesyuarat Agung Luar Biasa (EGM)',
                        'color_code' => '#008000',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Undang-Undang Kecil (House Rules)',
                        'color_code' => '#008000',
                        'sort_no' => 3,
                    ],
                    [
                        'name' => 'Ahli Jawatankuasa (AJK)',
                        'color_code' => '#008000',
                        'sort_no' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Pentadbiran & Pengurusan',
                'description' => '(Semua aduan berkaitan pentadbiran dan pengurusan Pemaju, Badan Pengurusan Bersama / Perbadanan Pengurusan (JMB / MC) & Ejen termasuk tanggungjawab, kerjasama dan pertikaian).',
                'color_code' => '#0000FF',
                'sort_no' => 2,
                'types' => [
                    [
                        'name' => 'Pejabat Pengurusan (MO)',
                        'color_code' => '#0000FF',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Ingkar Award (IP)',
                        'color_code' => '#0000FF',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Kontrak',
                        'color_code' => '#0000FF',
                        'sort_no' => 3,
                    ],
                    [
                        'name' => 'Kacau Ganggu',
                        'color_code' => '#0000FF',
                        'sort_no' => 4,
                    ],
                    [
                        'name' => 'Memorandum of Transfer (MOT)',
                        'color_code' => '#0000FF',
                        'sort_no' => 5,
                    ],
                    [
                        'name' => 'Akaun TNB / Syabas',
                        'color_code' => '#0000FF',
                        'sort_no' => 6,
                    ],
                    [
                        'name' => 'Keselamatan & Kesihatan',
                        'color_code' => '#0000FF',
                        'sort_no' => 7,
                    ],
                ],
            ],
            [
                'name' => 'Penyenggaraan dan Kerosakan',
                'description' => '(Semua aduan berkaitan penyenggaraan dan kerosakan bangunan dan harta bersama).',
                'color_code' => '#FFA500',
                'sort_no' => 3,
                'types' => [
                    [
                        'name' => 'Louvers',
                        'color_code' => '#FFA500',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Lif',
                        'color_code' => '#FFA500',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Sampah',
                        'color_code' => '#FFA500',
                        'sort_no' => 3,
                    ],
                    [
                        'name' => 'Kebocoran Di Dalam Unit',
                        'color_code' => '#FFA500',
                        'sort_no' => 4,
                    ],
                    [
                        'name' => 'Kebocoran Antara Tingkat (Interfloor Leakage)',
                        'color_code' => '#FFA500',
                        'sort_no' => 5,
                    ],
                    [
                        'name' => 'Pembentungan',
                        'color_code' => '#FFA500',
                        'sort_no' => 6,
                    ],
                    [
                        'name' => 'Pendawaian & Eletrik',
                        'color_code' => '#FFA500',
                        'sort_no' => 7,
                    ],
                    [
                        'name' => 'Gutter & Rain Water Down Part (RWDP)',
                        'color_code' => '#FFA500',
                        'sort_no' => 8,
                    ],
                    [
                        'name' => 'Lanskap',
                        'color_code' => '#FFA500',
                        'sort_no' => 9,
                    ],
                    [
                        'name' => 'Tangki Air',
                        'color_code' => '#FFA500',
                        'sort_no' => 10,
                    ],
                    [
                        'name' => 'Waterproofing',
                        'color_code' => '#FFA500',
                        'sort_no' => 11,
                    ],
                    [
                        'name' => 'Fasad Bangunan',
                        'color_code' => '#FFA500',
                        'sort_no' => 12,
                    ],

                ],
            ],
            [
                'name' => 'Kewangan',
                'description' => '(Semua aduan berkaitan kewangan termasuk Caj Penyenggaraan (MF), caruman Kumpulan Wang Penjelas (SF) dan Penyata Kewangan Beraudit).',
                'color_code' => '#800080',
                'sort_no' => 4,
                'types' => [
                    [
                        'name' => 'Laporan Audit',
                        'color_code' => '#800080',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Maintenance Fee / Sinking Fund (MF/SF)',
                        'color_code' => '#800080',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Laporan Kewangan (Bulanan / Tahunan)',
                        'color_code' => '#800080',
                        'sort_no' => 3,
                    ],
                    [
                        'name' => 'Insurans',
                        'color_code' => '#800080',
                        'sort_no' => 4,
                    ],

                ],
            ],
            [
                'name' => 'Khidmat Nasihat',
                'description' => '(Semua aduan berkaitan khidmat nasihat mengenai Akta Pengurusan Strata 2013 [Akta 757], Peraturan-Peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015 dan Kaedah Perolehan).',
                'color_code' => '#FFC0CB',
                'sort_no' => 5,
                'types' => [
                    [
                        'name' => 'Kaedah Perolehan',
                        'color_code' => '#FFC0CB',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Akta Pengurusan Strata 2013 [Akta 757]',
                        'color_code' => '#FFC0CB',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Peraturan-Peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015',
                        'color_code' => '#FFC0CB',
                        'sort_no' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Penguatkuasaan Pesuruhjaya Bangunan (COB)',
                'description' => '(Semua aduan yang berkaitan dengan pengubahsuaian unit, sitaan premis & ad-hoc lawatan tapak yang memerlukan kehadiran COB).',
                'color_code' => '#FF7518',
                'sort_no' => 6,
                'types' => [
                    [
                        'name' => 'Pengubahsuaian Dalaman Unit',
                        'color_code' => '#FF7518',
                        'sort_no' => 1,
                    ],
                    [
                        'name' => 'Sitaan',
                        'color_code' => '#FF7518',
                        'sort_no' => 2,
                    ],
                    [
                        'name' => 'Ad-hoc Lawatan Tapak Yang Memerlukan Kehadiran COB',
                        'color_code' => '#FF7518',
                        'sort_no' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Lain-lain',
                'description' => null,
                'color_code' => '#FFFFFF',
                'sort_no' => 7,
            ],
        ];

        foreach ($categories as $cat) {
            // Pisahkan data kategori & types
            $types = isset($cat['types']) ? $cat['types'] : [];
            unset($cat['types']);

            // Insert kategori
            $category = ComplaintCategory::create($cat);

            // Insert jenis aduan jika ada
            foreach ($types as $type) {
                $type['complaint_category_id'] = $category->id;
                ComplaintType::create($type);
            }
        }

        echo "ComplaintCategory & ComplaintType seeded.\n";
    }
}
