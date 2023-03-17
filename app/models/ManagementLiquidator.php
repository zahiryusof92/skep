<?php

class ManagementLiquidator extends Eloquent {

    protected $table = 'management_liquidators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'management_id',
        'name',
        'address_1',
        'address_2',
        'address_3',
        'address_4',
        'city',
        'poscode',
        'state',
        'country',
        'phone_no',
        'fax_no',
        'remarks',
    ];

    /**
     * Get the file that owns the management liquidator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(Files::class, 'file_id');
    }

    /**
     * Get the management that owns the management liquidator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function management()
    {
        return $this->belongsTo(Management::class, 'management_id');
    }

    public function countries() {
        return $this->belongsTo('Country', 'country');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }

    public function cities() {
        return $this->belongsTo('City', 'city');
    }

}
