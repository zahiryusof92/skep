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

    public function getRole() {
        return $this->belongsTo('Role', 'role');
    }

    public function getFile() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function getCOB() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function isSuperadmin() {
        if (stripos($this->getRole->name, Role::SUPERADMIN) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isCOB() {
        if (stripos($this->getRole->name, Role::COB) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isCOBPaid() {
        if (stripos($this->getRole->name, Role::COB) !== FALSE) {
            if ($this->getRole->is_paid) {
                return true;
            }
        }

        return false;
    }

    public function isCOBManager() {
        if (stripos($this->getRole->name, Role::COB_MANAGER) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isCOBManagerPaid() {
        if (stripos($this->getRole->name, Role::COB_MANAGER) !== FALSE) {
            if ($this->getRole->is_paid) {
                return true;
            }
        }

        return false;
    }

    public function getAdmin() {
        if ($this->getRole->is_admin == 1) {
            return true;
        } else if ($this->role == 1 || $this->role == 2) {
            return true;
        }

        return false;
    }

    public function isHR() {
        if (stripos($this->getRole->name, Role::HR) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isJMB() {
        if (stripos($this->getRole->name, Role::JMB) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isMC() {
        if (stripos($this->getRole->name, Role::MC) !== FALSE) {
            return true;
        }

        return false;
    }

    public function isLawyer() {
        if (stripos($this->getRole->name, Role::LAWYER) !== FALSE) {
            return true;
        }

        return false;
    }

    public static function getLawyer() {
        $lawyer = '';

        $role = Role::where('name', 'lawyer')->where('is_active', 1)->where('is_deleted', 0)->first();
        if ($role) {
            $lawyer = User::where('role', $role->id)->where('is_active', 1)->where('is_deleted', 0)->get();
        }

        return $lawyer;
    }

    public function getTotalPoint() {
        // $debit = PointTransaction::where('user_id', $this->id)->where('is_debit', 1)->sum('point_usage');
        // $credit = PointTransaction::where('user_id', $this->id)->where('is_debit', 0)->sum('point_usage');
        $point_transaction = PointTransaction::where('user_id', $this->id)->orderBy('created_at','desc')->first();
        $balance = round($point_transaction->point_balance, 0);

        return $balance;
    }

}
