<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFileMovementsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_movements', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_id')->default(0)->index();
			$table->integer('company_id')->default(0)->index();
			$table->text('assigned_to')->nullable();
			$table->text('remarks')->nullable();
			$table->boolean('is_deleted')->default(false);
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
		Schema::drop('file_movements');
	}
}
