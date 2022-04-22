<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFileSyncLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_sync_log', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->longText('data')->default(null);
			$table->integer('reference_file_id')->default(0);
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
		Schema::drop('file_sync_log');
	}

}
