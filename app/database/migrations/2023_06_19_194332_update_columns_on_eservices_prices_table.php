<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsOnEservicesPricesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_prices', function (Blueprint $table) {
			Schema::table('eservices_prices', function (Blueprint $table) {
				$table->dropColumn('price');
			});

			Schema::table('eservices_prices', function (Blueprint $table) {
				$table->double('price', 10, 2)->default(0)->nullable()->after('slug');
			});
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_prices', function (Blueprint $table) {
			Schema::table('eservices_prices', function (Blueprint $table) {
				$table->dropColumn('price');
			});

			Schema::table('eservices_prices', function (Blueprint $table) {
				$table->double('price')->default(0)->after('slug');
			});
		});
	}
}
