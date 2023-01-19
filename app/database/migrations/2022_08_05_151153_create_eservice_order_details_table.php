<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEserviceOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eservices_order_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('eservice_order_id')->nullable();			
			$table->string('type')->nullable();
			$table->longText('value')->nullable();
			$table->double('price')->default(0);
			$table->string('status')->nullable();
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
		Schema::drop('eservices_order_details');
	}

}
