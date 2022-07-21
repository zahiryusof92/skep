<?php

use Carbon\Carbon;
use Helper\KCurl;
use Illuminate\Support\Facades\Mail;

class TestController extends \BaseController
{
	public function testMail()
	{
		$delay = 0;

		return Mail::later(Carbon::now()->addSeconds($delay), 'emails.test', array(), function ($message) {
			$message->to('zahir@odesi.tech', 'Zahir | ODESI')->subject('Test E-mail');
		});
	}

	public function updateFinanceAPI()
	{

        $headers =  [
            "Content-Type: application/json",
            "Accept: application/json",
			'Authorization: Basic '. base64_encode("admin:P@ssw0rd")
        ];
		$jayParsedAry = [
			"file_no" => "MBPJ/030200/T/R2/COB/SS21/CD(2)/07",
			"year" => 2021,
			"month" => 12,
			"check" => [
				"date" => "2020-10-17",
				"name" => "test",
				"position" => "test_position",
				"is_active" => 1,
				"remarks" => ""
			],
			"report" => [
				"mf" => [
					"fee_sebulan" => 10,
					"unit" => 10,
					"fee_semasa" => 10,
					"tunggakan_belum_dikutip" => 10,
					"no_akaun" => 10,
					"nama_bank" => "aa",
					"baki_bank_awal" => 10,
					"baki_bank_akhir" => 10,
					"utility" => 10,
					"contract" => 10,
					"repair" => 10,
					"vandalisme" => 10,
					"staff" => 10,
					"admin" => 10,
					"extra" => [
						[
							"fee_sebulan" => 10,
							"unit" => 2,
							"fee_semasa" => 10
						]
					]
				],
				"sf" => [
					"fee_sebulan" => 10,
					"unit" => 10,
					"fee_semasa" => 10,
					"tunggakan_belum_dikutip" => 10,
					"no_akaun" => 10,
					"nama_bank" => "aa",
					"baki_bank_awal" => 10,
					"baki_bank_akhir" => 10,
					"repair" => 10,
					"vandalisme" => 10,
					"is_custom" => [
						[
							"name" => "Name",
							"amount" => 10
						],
						[
							"name" => "custom_test1",
							"amount" => 10
						]
					]
				]
			],
			"income" => [
				"main" => [
					[
						"default" => "sinking_fund",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10"
					]
				],
				"is_custom" => [
					[
						"name" => "testing name",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10"
					]
				]
			],
			"utility" => [
				"bhg_a" => [
					"main" => [
						[
							"default" => "bil_air",
							"tunggakan" => 10,
							"semasa" => 10,
							"hadapan" => 10,
							"tertunggak" => 10
						]
					],
					"is_custom" => [
						[
							"name" => "Testing Utility (BHG A)",
							"tunggakan" => 10,
							"semasa" => 10,
							"hadapan" => 10,
							"tertunggak" => 10
						]
					]
				],
				"bhg_b" => [
					"main" => [
						[
							"default" => "bil_meter_air",
							"tunggakan" => 10,
							"semasa" => 10,
							"hadapan" => 10,
							"tertunggak" => 10
						]
					],
					"is_custom" => [
						[
							"name" => "Testing Utility (BHG B)",
							"tunggakan" => 10,
							"semasa" => 10,
							"hadapan" => 10,
							"tertunggak" => 10
						]
					]
				]
			],
			"contract" => [
				"main" => [
					[
						"default" => "insurans",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				],
				"is_custom" => [
					[
						"name" => "testing name",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				]
			],
			"repair" => [
				"mf" => [
					"main" => [
						[
							"default" => "lif",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					]
				],
				"sf" => [
					"main" => [
						[
							"default" => "lif",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					],
					"is_custom" => [
						[
							"name" => "testing name",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					]
				]
			],
			"vandal" => [
				"mf" => [
					"main" => [
						[
							"default" => "lif",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					]
				],
				"sf" => [
					"main" => [
						[
							"default" => "lif",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					],
					"is_custom" => [
						[
							"name" => "testing name",
							"tunggakan" => "10",
							"semasa" => "10",
							"hadapan" => "10",
							"tertunggak" => 10
						]
					]
				]
			],
			"staff" => [
				"main" => [
					[
						"default" => "pegawal_keselamatan",
						"gaji_per_orang" => "10",
						"bil_pekerja" => "10",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				],
				"is_custom" => [
					[
						"name" => "test position",
						"gaji_per_orang" => "10",
						"bil_pekerja" => "10",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				]
			],
			"admin" => [
				"main" => [
					[
						"default" => "telefon_n_internet",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				],
				"is_custom" => [
					[
						"name" => "test perkara",
						"tunggakan" => "10",
						"semasa" => "10",
						"hadapan" => "10",
						"tertunggak" => 10
					]
				]
			]
		];


		$response = ((string) ((new KCurl())->requestPost(
			$headers,
			'https://test.odesi.tech/api/updateFinanceFile',
			json_encode($jayParsedAry)
		)));

		dd($response);
	}
}
