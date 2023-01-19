<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToEservicesOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->string('type')->nullable()->after('order_no');
			$table->longText('value')->nullable()->after('type');
			$table->string('bill_no')->nullable()->after('value');
			$table->date('date')->nullable()->after('bill_no');
			$table->double('price')->default(0)->after('date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->dropColumn('type');
			$table->dropColumn('value');
			$table->dropColumn('price');
			$table->dropColumn('bill_no');
			$table->dropColumn('date');
		});
	}
}
