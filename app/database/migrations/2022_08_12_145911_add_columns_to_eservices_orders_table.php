<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEservicesOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->integer('approval_by')->nullable()->after('status');
			$table->timestamp('approval_date')->nullable()->after('approval_by');
			$table->string('approval_remark')->nullable()->after('approval_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->dropColumn('approval_by');
			$table->dropColumn('approval_date');
			$table->dropColumn('approval_remark');
		});
	}
}
