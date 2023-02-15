<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEservicesOrderDetailsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_order_details', function (Blueprint $table) {
			$table->string('bill_no')->nullable()->after('type');
			$table->date('date')->nullable()->after('bill_no');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_order_details', function (Blueprint $table) {
			$table->dropColumn('bill_no');
			$table->dropColumn('date');
		});
	}
}
