<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLiquidatorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('liquidators', function($table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('phone_no')->nullable();
			$table->string('fax_no')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('address3')->nullable();
			$table->text('address4')->nullable();
			$table->integer('city')->nullable();
			$table->string('poscode')->nullable();
			$table->integer('state')->nullable();
			$table->integer('country')->nullable();
			$table->text('remarks')->nullable();
			$table->integer('is_active')->default(1);
			$table->integer('is_deleted')->default(0);
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
		Schema::drop('liquidators');
	}

}
