<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCobLettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cob_letters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->string('type')->nullable()->index();
			$table->date('date')->nullable()->index();
			$table->string('bill_no')->nullable()->index();
			$table->string('building_name')->nullable()->index();
			$table->string('receiver_name')->nullable()->index();
			$table->text('receiver_address_1')->nullable();
			$table->text('receiver_address_2')->nullable();
			$table->text('receiver_address_3')->nullable();
			$table->text('receiver_address_4')->nullable();
			$table->text('receiver_address_5')->nullable();
			$table->text('management_address_1')->nullable();
			$table->text('management_address_2')->nullable();
			$table->text('management_address_3')->nullable();
			$table->text('management_address_4')->nullable();
			$table->text('management_address_5')->nullable();
			$table->string('unit_name')->nullable()->index();
			$table->text('from_address_1')->nullable();
			$table->text('from_address_2')->nullable();
			$table->text('from_address_3')->nullable();
			$table->text('from_address_4')->nullable();
			$table->text('from_address_5')->nullable();
			$table->integer('causer_by')->default(0);
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
		Schema::drop('cob_letters');
	}

}
