<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AuditAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audit_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->default(0)->index();
			$table->integer('company_id')->default(0)->index();
			$table->integer('parent_id')->default(0)->index();
			$table->string('name')->nullable()->index();
			$table->date('submission_date')->nullable()->index();
			$table->date('closing_date')->nullable()->index();
			$table->decimal('income_collection', 10, 2)->nullable()->index();
			$table->decimal('expense_collection', 10, 2)->nullable()->index();
			$table->text('filename')->nullable();
			$table->boolean('is_deleted')->default(false)->index();
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
		Schema::drop('audit_accounts');
	}

}
