<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    protected $fillable = array(
        'full_name',
        'username',
        'password',
        'role'
    );

    use UserTrait,
        RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    public function getAdmin() {
        if ($this->getRole->is_admin == 1) {
            return true;
        } else if ($this->role == 1 || $this->role == 2) {
            return true;
        }

        return false;
    }

    public function getRole() {
        return $this->belongsTo('Role', 'role');
    }
    
    public function getFile() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function getCOB() {
        return $this->belongsTo('Company', 'company_id');
    }
}
