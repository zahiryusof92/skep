<?php

use Illuminate\Database\Eloquent\Builder;

class PointPackage extends Eloquent {

    protected $table = 'point_package';


    public function scopeSelf(Builder $builder) {
        return $builder->where('is_deleted', false);
    }
}
