<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddChecklistToDlpDepositsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dlp_deposits', function (Blueprint $table) {
			$table->text('checklist')->nullable()->after('maturity_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dlp_deposits', function (Blueprint $table) {
			$table->dropColumn('checklist');
		});
	}
}
