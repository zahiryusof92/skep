<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddLawyerColumnsToBuyerTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->string('lawyer_name')->nullable()->index()->after('proxy_phone');
			$table->text('lawyer_address')->nullable()->after('lawyer_name');
			$table->string('lawyer_fail_ref_no')->nullable()->index()->after('lawyer_address');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->dropColumn(['lawyer_name', 'lawyer_address', 'lawyer_fail_ref_no']);
		});
	}
}
