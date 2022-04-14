<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCommercialBlockExtraDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commercial_block_extra_draft', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->integer('unit_no')->nullable();
			$table->decimal('maintenance_fee', 10)->default(0)->index();
			$table->integer('maintenance_fee_option')->default(0)->index();
			$table->decimal('sinking_fund', 10)->default(0)->index();
			$table->integer('sinking_fund_option')->default(0)->index();
			$table->integer('is_deleted')->default(false)->index();
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
		Schema::drop('commercial_block_extra_draft');
	}

}
