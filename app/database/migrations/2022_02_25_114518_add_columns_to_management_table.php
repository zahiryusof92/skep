<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToManagementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('management', function (Blueprint $table) {
			$table->boolean('no_management')->default(false)->index()->after('is_developer');
			$table->date('start')->nullable()->after('no_management');
			$table->date('end')->nullable()->after('start');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('management', function (Blueprint $table) {
			$table->dropColumn('no_management');
			$table->dropColumn('start');
			$table->dropColumn('end');
		});
	}

}
