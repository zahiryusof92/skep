<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTitleToFileMovementTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_movements', function (Blueprint $table) {
			$table->string('title')->nullable()->after('assigned_to');
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
			$table->removeColumn('title');
		});
	}
}
