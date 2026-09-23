<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMeetingDocumentsTable extends Migration
{
    protected function documentSlugs()
    {
        return array(
            'notice_of_first_agm_form_5',
            'notice_of_agm_form_14',
            'notice_of_agm_form_16',
            'audited_account_form_8',
            'motion_form',
            'proxy_form',
            'persons_entitled_to_vote_list',
            'building_insurance_policy_copy',
            'house_rules_copy',
            'register_of_parcel_owners_form_9',
            'authorization_letter_company_owner',
            'notice_of_agm',
            'audited_account_report',
            'certified_agm_minutes_copy',
            'current_master_title_deed_copy',
            'agm_attendance_list',
            'committee_members_list_with_details',
            'committee_spa_copy',
            'non_bankruptcy_certificate',
            'complete_building_plan',
            'management_committee_integrity_pledge',
            'agm_minutes',
            'first_committee_meeting_minutes',
            'latest_audited_account',
            'agm_attendance_list_final',
            'approved_resolutions',
            'committee_details_package',
            'statutory_declaration',
            'non_bankruptcy_certificate_final',
            'authorization_letter_final',
            'notice_of_egm',
            'egm_proxy_form',
            'egm_persons_entitled_to_vote',
            'egm_minutes',
            'egm_first_committee_meeting_minutes',
            'egm_latest_audited_account',
            'egm_attendance_list',
            'egm_approved_resolutions',
            'egm_committee_details_package',
            'egm_statutory_declaration',
            'egm_non_bankruptcy_certificate',
            'egm_authorization_letter',
        );
    }

    public function up()
    {
        Schema::create('meeting_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->default(0)->index();
            $table->integer('company_id')->default(0)->index();
            $table->date('agm_date')->nullable()->index();
            $table->tinyInteger('agm_type')->default(1)->index();
            $table->tinyInteger('type')->default(1)->index();

            foreach ($this->documentSlugs() as $slug) {
                $table->boolean('is_' . $slug)->default(false);
                $table->text($slug . '_url')->nullable();
            }

            $table->string('audit_report')->nullable();
            $table->text('audit_report_url')->nullable();
            $table->date('audit_start_date')->nullable();
            $table->date('audit_end_date')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('meeting_documents');
    }
}
