<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropOcrsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('ocrs');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('ocrs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->default(0);
			$table->integer('meeting_document_id')->default(0);
			$table->string('type');
			$table->text('url');
			$table->integer('created_by')->default(0);
			$table->softDeletes();
			$table->timestamps();
		});
	}
}
