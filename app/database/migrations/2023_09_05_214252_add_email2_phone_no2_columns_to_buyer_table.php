<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddEmail2PhoneNo2ColumnsToBuyerTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('buyer', function (Blueprint $table) {
			$table->string('email2')->nullable()->after('ic_no2');
			$table->string('phone_no2')->nullable()->after('email2');
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
			$table->dropColumn(['email2', 'phone_no2']);
		});
	}
}
