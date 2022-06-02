<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddParkingColumnsToOthersDetailsDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('others_details_draft', function (Blueprint $table) {
			$table->string('parking_bay')->nullable()->index()->after('tnb');
			$table->string('parking_area')->nullable()->index()->after('parking_bay');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('others_details_draft', function (Blueprint $table) {
			$table->dropColumn('parking_bay');
			$table->dropColumn('parking_area');
		});
	}

}
