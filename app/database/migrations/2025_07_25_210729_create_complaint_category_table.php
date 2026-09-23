<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateComplaintCategoryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('complaint_categories', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->text('description')->nullable();
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
		Schema::drop('complaint_categories');
	}
}
