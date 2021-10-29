<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnToHouseSchemeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('house_scheme', function (Blueprint $table) {
			$table->integer('liquidator')->index()->after('developer');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('house_scheme', function (Blueprint $table) {
			$table->dropColumn('liquidator');
		});
	}

}
