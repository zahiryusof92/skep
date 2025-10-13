<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTppmTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tppms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(0);
            $table->integer('file_id')->default(0);
            $table->integer('strata_id')->default(0);
            $table->string('reference_no')->nullable();
            $table->string('cost_category')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('applicant_position')->nullable();
            $table->string('applicant_phone')->nullable();
            $table->string('applicant_email')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('organization_address_1')->nullable();
            $table->text('organization_address_2')->nullable();
            $table->text('organization_address_3')->nullable();
            $table->integer('parliament_id')->nullable()->default(0);
            $table->integer('dun_id')->nullable()->default(0);
            $table->integer('district_id')->nullable()->default(0);
            $table->decimal('first_purchase_price', 12, 2)->nullable()->default(0);
            $table->integer('year_built')->nullable();
            $table->integer('year_occupied')->nullable();
            $table->integer('num_blocks')->nullable()->default(0);
            $table->integer('num_units')->nullable()->default(0);
            $table->integer('num_units_occupied')->nullable()->default(0);
            $table->integer('num_units_owner')->nullable()->default(0);
            $table->integer('num_units_malaysian')->nullable()->default(0);
            $table->integer('num_storeys')->nullable()->default(0);
            $table->integer('num_residents')->nullable()->default(0);
            $table->integer('num_units_vacant')->nullable()->default(0);
            $table->integer('num_units_tenant')->nullable()->default(0);
            $table->integer('num_units_non_malaysian')->nullable()->default(0);
            $table->string('requested_block_name')->nullable();
            $table->integer('requested_block_no')->nullable()->default(0);
            $table->text('scope')->nullable();
            $table->text('spa_copy')->nullable();
            $table->text('detail_report')->nullable();
            $table->text('meeting_minutes')->nullable();
            $table->text('cost_estimate')->nullable();
            $table->string('status')->nullable();
            $table->integer('approval_by')->nullable()->default(0);
            $table->timestamp('approval_date')->nullable();
            $table->text('approval_remark')->nullable();
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
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
        Schema::drop('tppms');
    }
}
