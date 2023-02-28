<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateManagementLiquidatorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('management_liquidators', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default(0)->index();
			$table->integer('management_id')->default(0)->index();
			$table->string('name')->nullable()->index();
			$table->text('address_1')->nullable();
			$table->text('address_2')->nullable();
			$table->text('address_3')->nullable();
			$table->text('address_4')->nullable();
			$table->integer('city')->default(0)->index();
			$table->string('poscode')->nullable()->index();
			$table->integer('state')->default(0)->index();
			$table->integer('country')->default(0)->index();
			$table->string('phone_no')->nullable()->index();
			$table->string('fax_no')->nullable()->index();
			$table->text('remarks')->nullable();
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
		Schema::drop('management_liquidators');
	}

}
