<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDlpPeriodTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dlp_periods', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('duration')->default(0);
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
		Schema::drop('dlp_periods');
	}
}
