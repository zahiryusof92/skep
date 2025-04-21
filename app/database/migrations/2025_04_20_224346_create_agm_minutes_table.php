<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAgmMinutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agm_minutes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default(0)->index();
			$table->integer('company_id')->default(0)->index();
			$table->enum('type', ['jmb', 'mc'])->index()->nullable();
			$table->enum('agm_type', ['agm', 'egm'])->index()->nullable();
			$table->boolean('is_first')->default(false);
			$table->date('agm_date')->index()->nullable();
			$table->text('description')->nullable();
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
		Schema::drop('agm_minutes');
	}

}
