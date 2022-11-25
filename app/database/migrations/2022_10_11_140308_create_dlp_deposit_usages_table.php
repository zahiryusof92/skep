<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDlpDepositUsagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dlp_deposit_usages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('dlp_deposit_id')->default(0);
			$table->string('description')->nullable();
			$table->double('amount')->default(0);
			$table->double('amount_before')->default(0);
			$table->double('amount_after')->default(0);
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dlp_deposit_usages');
	}
}
