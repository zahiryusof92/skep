<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddBalanceToDlpDepositsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dlp_deposits', function (Blueprint $table) {
			$table->double('balance')->default(0)->after('amount');
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
			$table->dropColumn('balance');
		});
	}
}
