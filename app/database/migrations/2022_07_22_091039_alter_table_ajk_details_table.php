<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterTableAjkDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ajk_details', function (Blueprint $table) {
			$table->integer('strata_id')->defult(0)->index()->after('file_id');
			$table->string('allowance')->nullable()->index()->after('phone_no');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ajk_details', function (Blueprint $table) {
			$table->dropColumn('strata_id');
			$table->dropColumn('allowance');
		});
	}

}
