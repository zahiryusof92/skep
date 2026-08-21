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
        'module',
        'route',
        'description',
        'is_view'
    ];

    /**
     * List query: lean joins (files + strata only).
     * Admin without admin_cob sees all companies (use indexes for speed).
     */
    public function scopeSelf(Builder $builder) {
        $builder->leftJoin('files', 'files.id', '=', 'notifications.file_id')
                ->leftJoin('strata', 'strata.file_id', '=', 'files.id');

        if (!Auth::user()->getAdmin()) {
            $builder->where('notifications.user_id', Auth::user()->id);
        } else if (!empty(Session::get('admin_cob'))) {
            $builder->where('notifications.company_id', Session::get('admin_cob'));
        }

        return $builder->select([
            'notifications.id',
            'notifications.user_id',
            'notifications.company_id',
            'notifications.file_id',
            'notifications.module',
            'notifications.route',
            'notifications.description',
            'notifications.is_view',
            'notifications.created_at',
            'notifications.updated_at',
            'notifications.deleted_at',
            'files.file_no as file_no',
            'strata.name as strata',
        ]);
    }

    public function scopeNotView(Builder $builder) {
        $builder->where('notifications.is_view', false);
    }

    /**
     * Unread for bell — notifications table only (no joins).
     */
    public function scopeBellUnread(Builder $builder) {
        return $builder->where('user_id', Auth::user()->id)
            ->where('is_view', false);
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
