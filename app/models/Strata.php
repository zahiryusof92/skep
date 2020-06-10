<?php

class Strata extends Eloquent {

    protected $table = 'strata';

    public function strataName() {
        if ($this->name) {
            return $this->name;
        }

        return "(Not Set)";
    }

}
