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
        'cob',
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive()
    {
        return self::where('is_active', true);
    }

    /**
     * Types belonging to a COB (shared rows have a null cob).
     */
    public function scopeForCob($query, $cob)
    {
        $cob = strtoupper((string) $cob);

        return $query->where(function ($q) use ($cob) {
            $q->where('cob', $cob)->orWhereNull('cob');
        });
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
