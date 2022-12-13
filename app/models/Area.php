<?php

class Area extends Eloquent
{
    protected $table = 'area';

    protected $fillable = [
        'description',
        'is_active',
        'is_deleted'
    ];

    public function scopeSelf()
    {
        return self::where('is_active', true)->where('is_deleted', false);
    }
}
