<?php

class ManagementDraft extends Eloquent {

    protected $table = 'management_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'reference_id', 
    ];
    
    public function developer() {
        return $this->hasOne('ManagementDeveloperDraft', 'management_id');
    }

    public function jmb() {
        return $this->hasOne('ManagementJMBDraft', 'management_id');
    }

    public function mc() {
        return $this->hasOne('ManagementMCDraft', 'management_id');
    }
    
    public function agent() {
        return $this->hasOne('ManagementAgentDraft', 'management_id');
    }

    public function others() {
        return $this->hasOne('ManagementOthersDraft', 'management_id');
    }

}
