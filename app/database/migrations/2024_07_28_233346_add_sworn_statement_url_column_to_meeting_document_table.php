<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSwornStatementUrlColumnToMeetingDocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meeting_document', function (Blueprint $table) {
			$table->text('sworn_statement_url')->nullable()->after('integrity_pledge_url');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meeting_document', function (Blueprint $table) {
			$table->dropColumn('sworn_statement_url');
		});
	}

}
