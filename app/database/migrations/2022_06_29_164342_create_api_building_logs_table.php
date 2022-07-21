<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateApiBuildingLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_building_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('api_building_id')->default(0)->index();
			$table->integer('finance_file_id')->default(0)->index();
			$table->longText('remarks')->default(null);
			$table->softDeletes();
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
		Schema::drop('api_building_logs');
	}

}
