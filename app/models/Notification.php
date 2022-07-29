<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Notification extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'company_id',
        'file_id',
        'strata_id',
        'module',
        'route',
        'description',
        'is_view'
    ];

    public function scopeSelf(Builder $builder) {
        $builder->join('files', 'notifications.file_id', '=', 'files.id')
                ->join('strata', 'strata.file_id', '=', 'files.id')
                ->join('company', 'notifications.company_id', '=', 'company.id')
                ->join('users', 'notifications.user_id', '=', 'users.id');
        if (!Auth::user()->getAdmin()) {
            $builder->where('notifications.user_id', Auth::user()->id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder->where('notifications.company_id', Session::get('admin_cob'));
            }
        }
        return $builder->selectRaw('notifications.*, company.name as company, files.file_no as file_no, strata.name as strata, users.full_name as user');
    }

    public function scopeNotView(Builder $builder) {
        $builder->where('notifications.is_view', false);
    }

    /**
     * Get the company that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the file that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(Files::class, 'file_id');
    }

    /**
     * Get the user that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}