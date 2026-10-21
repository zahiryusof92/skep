<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddMbsjFieldsToComplaints extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('complaints', function (Blueprint $table) {
			$table->string('planning_block', 10)->nullable()->index();
			$table->string('mpp_zone', 100)->nullable()->index();
			$table->date('date_minuted')->nullable()->index();
			$table->string('complainant_name', 255)->nullable();
			$table->date('action_date')->nullable()->index();
			$table->string('resolution_method', 50)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('complaints', function (Blueprint $table) {
			$table->dropColumn('planning_block');
			$table->dropColumn('mpp_zone');
			$table->dropColumn('date_minuted');
			$table->dropColumn('complainant_name');
			$table->dropColumn('action_date');
			$table->dropColumn('resolution_method');
		});
	}
}
