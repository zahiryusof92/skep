<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTunggakanBelumDikutipColumnToFinanceFileReportOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finance_file_report_old', function (Blueprint $table) {
			$table->decimal('tunggakan_belum_dikutip', 10)->default(0)->index()->after('fee_semasa');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finance_file_report_old', function (Blueprint $table) {
			$table->dropColumn('tunggakan_belum_dikutip');
		});
	}

}
