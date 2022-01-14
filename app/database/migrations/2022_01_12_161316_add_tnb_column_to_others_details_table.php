<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTnbColumnToOthersDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('others_details', function (Blueprint $table) {
			$table->string('tnb')->nullable()->index()->after('water_meter');
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
			$table->dropColumn('tnb');
		});
	}

}
