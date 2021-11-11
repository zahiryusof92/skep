<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToHouseSchemeDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('house_scheme_draft', function (Blueprint $table) {
			$table->string('liquidator_name')->nullable()->index()->after('fax_no');
			$table->text('liquidator_address1')->nullable()->after('liquidator_name');
			$table->text('liquidator_address2')->nullable()->after('liquidator_address1');
			$table->text('liquidator_address3')->nullable()->after('liquidator_address2');
			$table->text('liquidator_address4')->nullable()->after('liquidator_address3');
			$table->string('liquidator_poscode', 45)->nullable()->index()->after('liquidator_address4');
			$table->integer('liquidator_city')->index()->after('liquidator_poscode');
			$table->integer('liquidator_state')->index()->after('liquidator_city');
			$table->integer('liquidator_country')->index()->after('liquidator_state');
			$table->string('liquidator_phone_no')->nullable()->index()->after('liquidator_country');
			$table->string('liquidator_fax_no')->nullable()->index()->after('liquidator_phone_no');
			$table->integer('liquidator_is_active')->index()->after('liquidator_fax_no');
			$table->text('liquidator_remarks')->nullable()->after('liquidator_is_active');
			$table->integer('is_liquidator')->index()->after('liquidator_remarks');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('house_scheme_draft', function (Blueprint $table) {
			$table->dropColumn('liquidator_name');
			$table->dropColumn('liquidator_address1');
			$table->dropColumn('liquidator_address2');
			$table->dropColumn('liquidator_address3');
			$table->dropColumn('liquidator_address4');
			$table->dropColumn('liquidator_poscode');
			$table->dropColumn('liquidator_city');
			$table->dropColumn('liquidator_state');
			$table->dropColumn('liquidator_country');
			$table->dropColumn('liquidator_phone_no');
			$table->dropColumn('liquidator_fax_no');
			$table->dropColumn('liquidator_is_active');
			$table->dropColumn('liquidator_remarks');
			$table->dropColumn('is_liquidator');
		});
	}

}
