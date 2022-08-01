<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddStrataIdColumnToScoringQualityIndexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('scoring_quality_index', function (Blueprint $table) {
			$table->integer('strata_id')->defult(0)->index()->after('file_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('scoring_quality_index', function (Blueprint $table) {
			$table->dropColumn('strata_id');
		});
	}

}
