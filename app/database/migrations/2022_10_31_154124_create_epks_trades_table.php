<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEpksTradesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('epks_trades', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->integer('epks_id')->default(0);
			$table->integer('epks_statement_id')->default(0);
			$table->date('date')->nullable();
			$table->double('amount')->default(0);
			$table->boolean('debit')->default(true);
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
		Schema::drop('epks_trades');
	}
}
