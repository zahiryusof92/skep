<?php

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
        $query = self::join('files', 'files.id', '=', 'file_drafts.id');
        if(!empty(Session::get('admin_cob'))) {
            $query = $query->where('files.company_id', Session::get('admin_cob'));
        }
        $total = $query->where('file_drafts.is_deleted', 0)
                    ->count();

        return $total;
    }
}
