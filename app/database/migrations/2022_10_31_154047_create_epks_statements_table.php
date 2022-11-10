<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEpksStatementsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('epks_statements', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->integer('epks_id')->default(0);
			$table->integer('month')->nullable();
			$table->integer('year')->nullable();
			$table->double('profit')->default(0);
			$table->integer('prepared_by')->default(0);
			$table->string('position_prepared_by')->nullable();
			$table->integer('approved_by')->default(0);
			$table->string('position_approved_by')->nullable();
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
		Schema::drop('epks_statements');
	}
}
