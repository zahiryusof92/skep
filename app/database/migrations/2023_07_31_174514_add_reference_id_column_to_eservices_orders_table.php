<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceIdColumnToEservicesOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->string('reference_id')->nullable()->after('price');
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
			$table->dropColumn('reference_id');
		});
	}

}
