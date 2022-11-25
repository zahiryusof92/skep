<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FileMovement extends Eloquent
{
    protected $table = 'file_movements';

    protected $fillable = [
        'file_id',
        'company_id',
        'strata',
        'assigned_to',
        'remarks',
        'is_deleted'
    ];

    public function scopeSelf()
    {
        $query = self::leftJoin('files', 'file_movements.file_id', '=', 'files.id')
            ->select(['file_movements.*']);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('file_movements.file_id', Auth::user()->file_id)
                    ->where('file_movements.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('file_movements.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('file_movements.company_id', Session::get('admin_cob'));
            }
        }
        return $query->where('file_movements.is_deleted', false)
            ->where('files.is_deleted', false);
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'assigned_to');
    }
}
