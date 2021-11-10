<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFinanceOld extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_check_old', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('finance_file_id')->nullable();
			$table->date('date')->nullable();
			$table->string('name')->nullable();
			$table->string('position')->nullable();
			$table->text('remarks')->nullable();
			$table->integer('is_active')->nullable();
			$table->timestamps();
		});

		Schema::create('finance_file_admin_old', function(Blueprint $table1)
		{
			$table1->increments('id');
			$table1->integer('finance_file_id')->nullable();
			$table1->string('name')->nullable();
			$table1->decimal('tunggakan',10, 2)->default(0);
			$table1->decimal('semasa',10, 2)->default(0);
			$table1->decimal('hadapan',10, 2)->default(0);
			$table1->decimal('tertunggak',10, 2)->default(0);
			$table1->integer('sort_no')->nullable();
			$table1->integer('is_custom')->default(0);
			$table1->timestamps();
		});

		Schema::create('finance_file_contract_old', function(Blueprint $table2)
		{
			$table2->increments('id');
			$table2->integer('finance_file_id')->nullable();
			$table2->string('name')->nullable();
			$table2->decimal('tunggakan',10, 2)->default(0);
			$table2->decimal('semasa',10, 2)->default(0);
			$table2->decimal('hadapan',10, 2)->default(0);
			$table2->decimal('tertunggak',10, 2)->default(0);
			$table2->integer('sort_no')->nullable();
			$table2->integer('is_custom')->default(0);
			$table2->timestamps();
		});

		Schema::create('finance_file_income_old', function(Blueprint $table3)
		{
			$table3->increments('id');
			$table3->integer('finance_file_id')->nullable();
			$table3->string('name')->nullable();
			$table3->decimal('tunggakan',10, 2)->default(0);
			$table3->decimal('semasa',10, 2)->default(0);
			$table3->decimal('hadapan',10, 2)->default(0);
			$table3->integer('sort_no')->nullable();
			$table3->integer('is_custom')->default(0);
			$table3->timestamps();
		});

		Schema::create('finance_file_repair_old', function(Blueprint $table4)
		{
			$table4->increments('id');
			$table4->integer('finance_file_id')->nullable();
			$table4->string('type', 20)->default("");
			$table4->string('name')->nullable();
			$table4->decimal('tunggakan',10, 2)->default(0);
			$table4->decimal('semasa',10, 2)->default(0);
			$table4->decimal('hadapan',10, 2)->default(0);
			$table4->decimal('tertunggak',10, 2)->default(0);
			$table4->integer('sort_no')->nullable();
			$table4->integer('is_custom')->default(0);
			$table4->timestamps();
		});

		Schema::create('finance_file_report_old', function(Blueprint $table5)
		{
			$table5->increments('id');
			$table5->integer('finance_file_id')->nullable();
			$table5->string('type', 20)->default("");
			$table5->string('fee_sebulan')->nullable();
			$table5->string('unit')->nullable();
			$table5->decimal('fee_semasa',10, 2)->default(0);
			$table5->string('no_akaun')->nullable();
			$table5->string('nama_bank')->nullable();
			$table5->decimal('baki_bank_akhir',10, 2)->default(0);
			$table5->decimal('baki_bank_awal',10, 2)->default(0);
			$table5->timestamps();
		});

		Schema::create('finance_file_report_extra_old', function(Blueprint $table6)
		{
			$table6->increments('id');
			$table6->integer('finance_file_id')->nullable();
			$table6->string('type', 20)->default("");
			$table6->decimal('fee_sebulan',10, 2)->default(0);
			$table6->integer('unit')->default(0);
			$table6->decimal('fee_semasa',10, 2)->default(0);
			$table6->timestamps();
		});

		Schema::create('finance_file_report_perbelanjaan_old', function(Blueprint $table7)
		{
			$table7->increments('id');
			$table7->integer('finance_file_id')->nullable();
			$table7->string('type', 20)->default("");
			$table7->string('name')->default("");
			$table7->string('report_key')->default("");
			$table7->decimal('amount',10, 2)->default(0);
			$table7->integer('sort_no')->default(0);
			$table7->integer('is_custom')->default(0);
			$table7->timestamps();
		});

		Schema::create('finance_file_staff_old', function(Blueprint $table8)
		{
			$table8->increments('id');
			$table8->integer('finance_file_id')->nullable();
			$table8->string('name')->default("");
			$table8->decimal('gaji_per_orang',10, 2)->default(0);
			$table8->integer('bil_pekerja')->nullable();
			$table8->decimal('tunggakan',10, 2)->default(0);
			$table8->decimal('semasa',10, 2)->default(0);
			$table8->decimal('hadapan',10, 2)->default(0);
			$table8->decimal('tertunggak',10, 2)->default(0);
			$table8->integer('sort_no')->default(0);
			$table8->integer('is_custom')->default(0);
			$table8->timestamps();
		});

		Schema::create('finance_file_summary_old', function(Blueprint $table9)
		{
			$table9->increments('id');
			$table9->integer('finance_file_id')->nullable();
			$table9->string('name')->default("");
			$table9->string('summary_key')->default("");
			$table9->decimal('amount',10, 2)->default(0);
			$table9->integer('sort_no')->default(0);
			$table9->timestamps();
		});

		Schema::create('finance_file_utility_old', function(Blueprint $table10)
		{
			$table10->increments('id');
			$table10->integer('finance_file_id')->nullable();
			$table10->string('type', 50)->default("");
			$table10->string('name')->default("");
			$table10->decimal('tunggakan',10, 2)->default(0);
			$table10->decimal('semasa',10, 2)->default(0);
			$table10->decimal('hadapan',10, 2)->default(0);
			$table10->decimal('tertunggak',10, 2)->default(0);
			$table10->integer('sort_no')->default(0);
			$table10->integer('is_custom')->default(0);
			$table10->timestamps();
		});

		Schema::create('finance_file_vandalisme_old', function(Blueprint $table11)
		{
			$table11->increments('id');
			$table11->integer('finance_file_id')->nullable();
			$table11->string('type', 50)->default("");
			$table11->string('name')->default("");
			$table11->decimal('tunggakan',10, 2)->default(0);
			$table11->decimal('semasa',10, 2)->default(0);
			$table11->decimal('hadapan',10, 2)->default(0);
			$table11->decimal('tertunggak',10, 2)->default(0);
			$table11->integer('sort_no')->default(0);
			$table11->integer('is_custom')->default(0);
			$table11->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finance_check_old');
		Schema::drop('finance_file_admin_old');
		Schema::drop('finance_file_contract_old');
		Schema::drop('finance_file_income_old');
		Schema::drop('finance_file_repair_old');
		Schema::drop('finance_file_report_old');
		Schema::drop('finance_file_report_extra_old');
		Schema::drop('finance_file_report_perbelanjaan_old');
		Schema::drop('finance_file_staff_old');
		Schema::drop('finance_file_summary_old');
		Schema::drop('finance_file_utility_old');
		Schema::drop('finance_file_vandalisme_old');
	}

}
