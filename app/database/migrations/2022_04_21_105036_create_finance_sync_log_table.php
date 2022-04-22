<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFinanceSyncLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_sync_log', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->integer('finance_file_id')->default(0);
			$table->longText('data')->default(null);
			$table->integer('reference_file_id')->default(0);
			$table->integer('reference_finance_file_id')->default(0);
			$table->string('status')->default(null);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finance_sync_log');
	}

}
