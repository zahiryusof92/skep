<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterTableInsuranceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('insurance', function (Blueprint $table) {
			$table->integer('strata_id')->defult(0)->index()->after('file_id');
			$table->text('filename')->nullable()->after('fic_validity_to');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('insurance', function (Blueprint $table) {
			$table->dropColumn('strata_id');
			$table->dropColumn('filename');
		});
	}

}
