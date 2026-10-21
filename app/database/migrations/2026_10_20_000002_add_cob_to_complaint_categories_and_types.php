<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCobToComplaintCategoriesAndTypes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('complaint_categories', function (Blueprint $table) {
			$table->string('cob', 10)->nullable()->index();
		});

		Schema::table('complaint_types', function (Blueprint $table) {
			$table->string('cob', 10)->nullable()->index();
		});

		// Existing records belong to the Putrajaya / MPKL set.
		DB::table('complaint_categories')->whereNull('cob')->update(['cob' => 'MPKL']);
		DB::table('complaint_types')->whereNull('cob')->update(['cob' => 'MPKL']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('complaint_categories', function (Blueprint $table) {
			$table->dropColumn('cob');
		});

		Schema::table('complaint_types', function (Blueprint $table) {
			$table->dropColumn('cob');
		});
	}
}
