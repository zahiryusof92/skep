<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddOriginalPriceColumnToOthersDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('others_details', function (Blueprint $table) {
			$table->string('original_price')->default(0)->index()->after('water_meter');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('others_details', function (Blueprint $table) {
			$table->dropColumn('original_price');
		});
	}

}
