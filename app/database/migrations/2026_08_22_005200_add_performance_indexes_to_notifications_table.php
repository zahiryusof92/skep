<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notifications', function(Blueprint $table)
		{
			$table->index(['company_id', 'created_at'], 'notifications_company_created_index');
			$table->index(['user_id', 'is_view', 'created_at'], 'notifications_user_view_created_index');
			$table->index(['created_at'], 'notifications_created_at_index');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notifications', function(Blueprint $table)
		{
			$table->dropIndex('notifications_company_created_index');
			$table->dropIndex('notifications_user_view_created_index');
			$table->dropIndex('notifications_created_at_index');
		});
	}

}
