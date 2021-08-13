<?php

class Management extends Eloquent {

    protected $table = 'management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id', 
    ];

    public function developer() {
        return $this->hasOne('ManagementDeveloper', 'management_id');
    }

    public function jmb() {
        return $this->hasOne('ManagementJMB', 'management_id');
    }

    public function mc() {
        return $this->hasOne('ManagementMC', 'management_id');
    }
    
    public function agent() {
        return $this->hasOne('ManagementAgent', 'management_id');
    }

    public function others() {
        return $this->hasOne('ManagementOthers', 'management_id');
    }

    public function draft() {
        return $this->hasOne('ManagementDraft', 'reference_id');
    }

}
