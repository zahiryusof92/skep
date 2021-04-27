<?php

class OtherDetails extends Eloquent {

    protected $table = 'others_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];
    
    public function draft() {
        return $this->hasOne('OtherDetailsDraft', 'reference_id');
    }

}
