<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAuditTrailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('audit_trail', function (Blueprint $table) {
			$table->integer('file_id')->default(0)->index()->after('id');
			$table->integer('company_id')->default(0)->index()->after('file_id');
			$table->text('agent')->nullable()->after('remarks');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('audit_trail', function (Blueprint $table) {
			$table->dropColumn('file_id');
			$table->dropColumn('company_id');
			$table->dropColumn('agent');
		});
	}

}
