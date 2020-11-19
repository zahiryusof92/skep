<?php

class HousingSchemeUser extends Eloquent {

    protected $table = 'housing_scheme_user';

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
