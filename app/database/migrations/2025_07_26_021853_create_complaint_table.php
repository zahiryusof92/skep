<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('complaints', function ($table) {
			$table->increments('id');
			$table->integer('file_id')->nullable();
			$table->integer('complaint_category_id')->nullable();
			$table->integer('complaint_type_id')->nullable();
			$table->string('name', 255);
			$table->text('description')->nullable();
			$table->text('attachment_url')->nullable();
			$table->string('letter_ref_no', 255)->nullable();
			$table->date('date_received')->nullable();
			$table->string('scheme_address', 255)->nullable();
			$table->string('scheme_zone', 255)->nullable();
			$table->string('scheme_receiver', 255)->nullable();
			$table->string('complainant_phone_no', 255)->nullable();
			$table->string('complainant_email', 255)->nullable();
			$table->string('complainant_ic_no', 255)->nullable();
			$table->string('complainant_type', 255)->nullable();
			$table->string('complainant_type_others', 255)->nullable();
			$table->string('complaint_category', 255)->nullable();
			$table->string('complaint_complication', 255)->nullable();
			$table->string('complaint_status', 255)->nullable();
			$table->string('action_duration', 255)->nullable();
			$table->text('officer_review')->nullable();
			$table->integer('status')->default(0);
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
		Schema::drop('complaints');
	}
}
