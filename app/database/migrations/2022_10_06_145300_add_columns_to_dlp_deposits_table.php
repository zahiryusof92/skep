<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDlpDepositsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dlp_deposits', function (Blueprint $table) {
			$table->string('type')->nullable()->after('user_id');
			$table->double('development_cost')->default(0)->after('type');
			$table->date('start_date')->nullable()->after('amount');
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
			$table->dropColumn('type');
			$table->dropColumn('development_cost');
			$table->dropColumn('start_date');
		});
	}
}
