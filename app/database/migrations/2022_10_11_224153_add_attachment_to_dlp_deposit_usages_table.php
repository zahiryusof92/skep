<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddAttachmentToDlpDepositUsagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dlp_deposit_usages', function (Blueprint $table) {
			$table->text('attachment')->nullable()->after('amount_after');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dlp_deposit_usages', function (Blueprint $table) {
			$table->dropColumn('attachment');
		});
	}
}
