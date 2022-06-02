<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIsSummaryColumnToFinanceFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finance_file', function (Blueprint $table) {
			$table->boolean('is_summary')->default(false)->index()->after('from_api');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finance_file', function (Blueprint $table) {
			$table->dropColumn('is_summary');
		});
	}

}
