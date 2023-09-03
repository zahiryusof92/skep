<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEservicesOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->dropColumn('price');
		});

		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->double('price', 10, 2)->default(0)->nullable()->after('date');
			$table->string('jana_bil_no_akaun')->nullable()->after('price');
			$table->text('jana_bil_response')->nullable()->after('jana_bil_no_akaun');
			$table->timestamp('jana_bil_created_at')->nullable()->after('jana_bil_response');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->dropColumn('price');
		});

		Schema::table('eservices_orders', function (Blueprint $table) {
			$table->double('price')->default(0)->after('date');
			$table->dropColumn('jana_bil_no_akaun');
			$table->dropColumn('jana_bil_response');
			$table->dropColumn('jana_bil_created_at');
		});
	}
}
