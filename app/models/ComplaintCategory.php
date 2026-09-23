<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ComplaintCategory extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'complaint_categories';

    protected $fillable = [
        'name',
        'description',
        'color_code',
        'sort_no',
        'is_active',
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive()
    {
        return self::where('is_active', true);
    }

    public function types()
    {
        return $this->hasMany('ComplaintType', 'complaint_category_id');
    }

    public function complaints()
    {
        return $this->hasMany('Complaint', 'complaint_category_id');
    }
}
