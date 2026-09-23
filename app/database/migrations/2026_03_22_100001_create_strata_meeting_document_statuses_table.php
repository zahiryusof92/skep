<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateStrataMeetingDocumentStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('strata_meeting_document_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('strata_meeting_document_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('status', 45)->default('pending');
            $table->longText('reason')->nullable();
            $table->string('endorsed_by')->nullable();
            $table->string('endorsed_email')->nullable();
            $table->text('attachment')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamps();

            // Short name: auto index name exceeds MySQL 64-char limit
            $table->index('strata_meeting_document_id', 'smds_doc_id_idx');
        });
    }

    public function down()
    {
        Schema::drop('strata_meeting_document_statuses');
    }
}
