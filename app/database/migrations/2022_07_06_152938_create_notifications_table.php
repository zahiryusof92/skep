<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->default(0)->index();
			$table->integer('company_id')->default(0)->index();
			$table->integer('file_id')->default(0)->index();
			$table->string('module')->nullable()->index();
			$table->string('route')->nullable()->index();
			$table->text('description')->nullable();
			$table->boolean('is_view')->default(false)->index();
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
		Schema::drop('notifications');
	}

}
