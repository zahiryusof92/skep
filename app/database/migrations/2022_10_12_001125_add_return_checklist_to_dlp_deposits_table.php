<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddReturnChecklistToDlpDepositsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dlp_deposits', function (Blueprint $table) {
			$table->text('return_checklist')->nullable()->after('checklist');
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
			$table->dropColumn('return_checklist');
		});
	}
}
