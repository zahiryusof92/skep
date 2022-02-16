<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIsHiddenToCompanyTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company', function (Blueprint $table) {
			$table->boolean('is_hidden')->default(false)->after('is_main');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company', function (Blueprint $table) {
			$table->dropColumn('is_hidden');
		});
	}
}
