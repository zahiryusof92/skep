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
        'cob',
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive()
    {
        return self::where('is_active', true);
    }

    /**
     * Categories belonging to a COB (shared rows have a null cob).
     */
    public function scopeForCob($query, $cob)
    {
        $cob = strtoupper((string) $cob);

        return $query->where(function ($q) use ($cob) {
            $q->where('cob', $cob)->orWhereNull('cob');
        });
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
