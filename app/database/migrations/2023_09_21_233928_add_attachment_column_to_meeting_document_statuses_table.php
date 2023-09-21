<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentColumnToMeetingDocumentStatusesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meeting_document_statuses', function (Blueprint $table) {
			$table->text('attachment')->nullable()->after('endorsed_email');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meeting_document_statuses', function (Blueprint $table) {
			$table->dropColumn('attachment');
		});
	}
}
