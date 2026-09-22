<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ComplaintType extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'complaint_types';

    protected $fillable = [
        'complaint_category_id',
        'name',
        'color_code',
        'sort_no',
        'is_active',
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive()
    {
        return self::where('is_active', true);
    }

    public function category()
    {
        return $this->belongsTo('ComplaintCategory', 'complaint_category_id');
    }

    public function complaints()
    {
        return $this->hasMany('Complaint', 'complaint_type_id');
    }
}
