<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumns1ToManagementDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('management_draft', function (Blueprint $table) {
			$table->string('liquidator')->default(false)->index()->after('end');
			$table->string('under_10_units')->default(false)->index()->after('liquidator');
			$table->longText('under_10_units_remarks')->nullable()->after('under_10_units');
			$table->string('bankruptcy')->default(false)->index()->after('under_10_units_remarks');
			$table->longText('bankruptcy_remarks')->nullable()->after('bankruptcy');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('management_draft', function (Blueprint $table) {
			$table->dropColumn('liquidator');
			$table->dropColumn('under_10_units');
			$table->dropColumn('under_10_units_remarks');
			$table->dropColumn('bankruptcy');
			$table->dropColumn('bankruptcy_remarks');
		});
	}

}
