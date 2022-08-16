<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEservicePricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eservices_prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->string('type')->nullable();
			$table->string('slug')->nullable();
			$table->double('price')->default(0);
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
		Schema::drop('eservices_prices');
	}

}
