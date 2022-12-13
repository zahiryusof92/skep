<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePostponedAgmReason extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('postponed_agm_reasons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name');
			$table->integer('sort')->default(0);
			$table->boolean('active')->default(true);
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
		Schema::drop('postponed_agm_reasons');
	}

}
