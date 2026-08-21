<?php

class AgmMinuteStatus extends Eloquent
{
    protected $table = 'agm_minute_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agm_minute_id',
        'user_id',
        'status',
        'reason',
        'endorsed_by',
        'endorsed_email',
        'attachment',
        'is_deleted',
    ];

    public function agmMinute()
    {
        return $this->belongsTo('AGMMinute', 'agm_minute_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
