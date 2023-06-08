<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFileMovementUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_movement_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('file_movement_id')->default(0)->index();
			$table->integer('user_id')->default(0)->index();
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
		Schema::drop('file_movement_users');
	}
}
