<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEpksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('epks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->string('email')->nullable();
			$table->text('address_1');
			$table->text('address_2');
			$table->text('address_3');
			$table->text('place_proposal');
			$table->text('sketch_proposal');
			$table->text('filename');
			$table->text('remarks');
			$table->enum('status', [0, 1, 2, 3, 4])->default(0);
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
		Schema::drop('epks');
	}

}
