<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPostponedAgmTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('postponed_agms', function(Blueprint $table)
		{			
			$table->date('agm_date')->nullable()->after('application_no');
			$table->date('new_agm_date')->nullable()->after('agm_date');
			$table->integer('postponed_agm_reason_id')->default(0)->after('new_agm_date');
			$table->text('approval_attachment')->nullable()->after('approval_remark');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('postponed_agms', function(Blueprint $table)
		{
			$table->dropColumn('agm_date');
			$table->dropColumn('new_agm_date');
			$table->dropColumn('postponed_agm_reason_id');
			$table->dropColumn('approval_attachment');
		});
	}

}
