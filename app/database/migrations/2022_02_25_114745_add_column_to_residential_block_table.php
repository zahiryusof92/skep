<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnToResidentialBlockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('residential_block', function (Blueprint $table) {
			$table->boolean('under_ten_units')->default(false)->index()->after('strata_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('residential_block', function (Blueprint $table) {
			$table->dropColumn('under_ten_units');
		});
	}

}
