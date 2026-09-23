<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTypeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('complaint_types', function ($table) {
			$table->increments('id');
			$table->integer('complaint_category_id')->default(0)->index();
			$table->string('name');
			$table->string('color_code', 10)->nullable();
			$table->integer('sort_no')->nullable();
			$table->boolean('is_active')->default(true);
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
		Schema::drop('complaint_types');
	}
}
