<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FileMovementUser extends Eloquent
{
    protected $table = 'file_movement_users';

    protected $fillable = [
        'file_movement_id',
        'user_id',
    ];

    public function fileMovement()
    {
        return $this->belongsTo('FileMovement', 'file_movement_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
