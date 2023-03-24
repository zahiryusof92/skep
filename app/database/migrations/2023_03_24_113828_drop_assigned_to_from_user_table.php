<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropAssignedToFromUserTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_movements', function (Blueprint $table) {
			$table->dropColumn('assigned_to');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('file_movements', function (Blueprint $table) {
			$table->text('assigned_to')->nullable()->after('company_id');
		});
	}
}
