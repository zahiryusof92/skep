<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEservicesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eservices', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->nullable();
			$table->string('type')->nullable();
			$table->date('date')->nullable();
			$table->longText('value')->nullable();
			$table->integer('causer_by')->nullable();
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
		Schema::drop('eservices');
	}
}
