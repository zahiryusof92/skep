<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFileDraftRejectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_draft_rejects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default('0')->index();
			$table->string('type')->default('0')->index();
			$table->text('remarks')->nullable();
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
		Schema::drop('file_draft_rejects');
	}

}
