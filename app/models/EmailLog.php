<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmailLog extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'email_logs';

    protected $fillable = [
        'user_id',
        'company_id',
        'file_id',
        'strata_id',
        'route',
        'description'
    ];

    public function scopeSelf(Builder $builder) {
        $builder->join('files', 'email_logs.file_id', '=', 'files.id')
                ->join('strata', 'strata.file_id', '=', 'files.id')
                ->join('company', 'email_logs.company_id', '=', 'company.id')
                ->join('users', 'email_logs.user_id', '=', 'users.id');
        if (!Auth::user()->getAdmin()) {
            $builder->where('user_id', Auth::user()->id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder->where('company_id', Session::get('admin_cob'));
            }
        }
        return $builder->selectRaw('email_logs.*, company.name as company, files.file_no as file_no, strata.name as strata, users.full_name as fullname');
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