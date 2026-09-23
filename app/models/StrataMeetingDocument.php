<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class StrataMeetingDocument extends Eloquent
{
    const IS_AGM = 1;

    const IS_EGM = 2;

    const FIRST_AGM = 1;

    const SECOND_AGM = 2;

    protected $table = 'meeting_documents';

    protected $guarded = array('id');

    public static function documentSlugs()
    {
        return Config::get('strata_meeting_documents.document_slugs', array());
    }

    public function scopeSelf($query)
    {
        $query = $query->join('files', 'meeting_documents.file_id', '=', 'files.id')
            ->select(array('meeting_documents.*'));

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->where('meeting_documents.file_id', Auth::user()->file_id)
                    ->where('meeting_documents.company_id', Auth::user()->company_id);
            } else {
                $query->where('meeting_documents.company_id', Auth::user()->company_id);
            }
        } elseif (!empty(Session::get('admin_cob'))) {
            $query->where('meeting_documents.company_id', Session::get('admin_cob'));
        }

        return $query->where('meeting_documents.is_deleted', false)
            ->where('files.is_deleted', false);
    }

    public function scopeMpklOnly($query)
    {
        return $query->join('company', 'meeting_documents.company_id', '=', 'company.id')
            ->where('company.short_name', 'MPKL')
            ->where('company.is_deleted', 0);
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function strataMeetingDocumentStatus()
    {
        return $this->hasOne('StrataMeetingDocumentStatus', 'strata_meeting_document_id')->where('is_deleted', 0);
    }

    public function getEnabledDocumentSlugs()
    {
        $enabled = array();
        foreach (self::documentSlugs() as $slug) {
            $flag = 'is_' . $slug;
            if (!empty($this->{$flag})) {
                $enabled[] = $slug;
            }
        }

        return $enabled;
    }
}
