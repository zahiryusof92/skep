<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEservicesOrderTransactionsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eservices_order_transactions', function (Blueprint $table) {
			$table->dropColumn('total_price');
		});

		Schema::table('eservices_order_transactions', function (Blueprint $table) {
			$table->double('payment_amount', 10, 2)->default(0)->nullable()->after('payment_method');
			$table->string('payment_receipt_no')->nullable()->after('payment_amount');
			$table->text('payment_response')->nullable()->after('payment_receipt_no');
			$table->timestamp('payment_created_at')->nullable()->after('payment_response');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eservices_order_transactions', function (Blueprint $table) {
			$table->dropColumn('payment_amount');
		});

		Schema::table('eservices_order_transactions', function (Blueprint $table) {
			$table->double('total_price')->default(0)->after('payment_method');
			$table->dropColumn('payment_receipt_no');
			$table->dropColumn('payment_response');
			$table->dropColumn('payment_created_at');
		});
	}
}
