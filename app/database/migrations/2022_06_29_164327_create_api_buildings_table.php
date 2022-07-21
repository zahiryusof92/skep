<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateApiBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_buildings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('client_id')->default(0);
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->boolean('status')->default(true);
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
		Schema::drop('api_buildings');
	}

}
