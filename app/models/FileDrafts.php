<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FileDrafts extends Eloquent {

    protected $table = 'file_drafts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];

    public static function getTotalPending() {
        $query = self::join('files', 'files.id', '=', 'file_drafts.file_id');
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('files.id', Auth::user()->file_id)
                                ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('files.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('files.company_id', Session::get('admin_cob'));
            }
        }
        $total = $query->where('file_drafts.is_deleted', 0)
                    ->count();

        return $total;
    }
}
