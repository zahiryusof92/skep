<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddStrataColumnToFileMovementsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_movements', function (Blueprint $table) {
			$table->string('strata')->nullable()->index()->after('company_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('file_movements', function (Blueprint $table) {
			$table->dropColumn('strata');
		});
	}
}
