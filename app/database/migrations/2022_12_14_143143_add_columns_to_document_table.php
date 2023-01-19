<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDocumentTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('document', function (Blueprint $table) {
			$table->string('status')->nullable()->after('is_readonly');
			$table->integer('approval_by')->nullable()->after('status');
			$table->timestamp('approval_date')->nullable()->after('approval_by');
			$table->text('approval_remark')->nullable()->after('approval_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('document', function (Blueprint $table) {
			$table->dropColumn(['status', 'approval_by', 'approval_date', 'approval_remark']);
		});
	}
}
