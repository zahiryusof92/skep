<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMeetingDocumentStatusTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meeting_document_statuses', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('meeting_document_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->string('status', 45)->default('pending');
			$table->longText('reason')->nullable();
			$table->string('endorsed_by')->nullable();
			$table->string('endorsed_email')->nullable();
			$table->integer('is_deleted')->default(0);
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
		Schema::drop('meeting_document_statuses');
	}
}
