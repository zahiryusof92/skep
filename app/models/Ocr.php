<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Ocr extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'ocrs';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function file()
    {
        return $this->belongsTo(Files::class, 'file_id');
    }

    public function strata()
    {
        return $this->belongsTo(Strata::class, 'strata_id');
    }

    public function meetingDocument()
    {
        return $this->belongsTo(MeetingDocument::class, 'meeting_document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
