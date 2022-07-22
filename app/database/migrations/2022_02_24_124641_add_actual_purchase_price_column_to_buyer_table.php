<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddActualPurchasePriceColumnToBuyerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->decimal('actual_purchase_price')->nullable()->index()->after('unit_share');
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
			$table->dropColumn('actual_purchase_price');
		});
	}

}
