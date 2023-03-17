<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Ocr extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'ocrs';

    protected $fillable = array(
        'company_id',
        'file_id',
        'strata_id',
        'meeting_document_id',
        'type',
        'url',
        'created_by',
    );

    public function scopeself(Builder $builder)
    {
        $query = $builder->with('meetingDocument');
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->whereHas('file', function ($q) {
                    $q->where('files.id', Auth::user()->file_id);
                });
                $query->whereHas('company', function ($q) {
                    $q->where('company.id', Auth::user()->company_id);
                });
            } else {
                $query->whereHas('file', function ($q) {
                    //
                });
                $query->whereHas('company', function ($q) {
                    $q->where('company.id', Auth::user()->company_id);
                });
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query->whereHas('file', function ($q) {
                    $q->where('files.company_id', Session::get('admin_cob'));
                });
                $query->whereHas('company', function ($q) {
                    //
                });
            } else {
                $query->whereHas('file', function ($q) {
                    //
                });
                $query->whereHas('company', function ($q) {
                    //
                });
            }
        }

        return $query;
    }

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
