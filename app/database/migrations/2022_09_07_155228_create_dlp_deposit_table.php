<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDlpDepositTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dlp_deposits', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->integer('file_id')->default(0);
			$table->integer('strata_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->double('amount')->default(0);
			$table->date('maturity_date')->nullable();
			$table->text('attachment')->nullable();
			$table->string('status')->nullable();
			$table->integer('approval_by')->nullable();
			$table->timestamp('approval_date')->nullable();
			$table->string('approval_remark')->nullable();
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
		Schema::drop('dlp_deposits');
	}
}
