<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBuyerTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->string('nama3')->nullable()->after('phone_no2');
			$table->string('ic_no3')->nullable()->after('nama3');
			$table->string('email3')->nullable()->after('ic_no3');
			$table->string('phone_no3')->nullable()->after('email3');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->dropColumn(['nama3', 'ic_no3', 'email3', 'phone_no3']);
		});
	}
}
