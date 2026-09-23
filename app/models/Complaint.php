<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Complaint extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'complaints';

    protected $fillable = [
        'file_id',
        'complaint_category_id',
        'complaint_type_id',
        'name',
        'description',
        'attachment_url',
        'letter_ref_no',
        'date_received',
        'scheme_address',
        'scheme_zone',
        'scheme_receiver',
        'complainant_phone_no',
        'complainant_email',
        'complainant_ic_no',
        'complainant_type',
        'complainant_type_others',
        'complaint_category',
        'complaint_complication',
        'complaint_status',
        'action_duration',
        'officer_review',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function category()
    {
        return $this->belongsTo('ComplaintCategory', 'complaint_category_id');
    }

    public function type()
    {
        return $this->belongsTo('ComplaintType', 'complaint_type_id');
    }
}
