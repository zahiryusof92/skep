<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class APIBuildingLog extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'api_building_logs';

    protected $fillable = [
        'api_building_id',
        'finance_file_id',
        'remarks'
    ];

    /**
     * Get the building that owns the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo(APIBuilding::class, 'api_building_id');
    }

    /**
     * Get the finance that owns the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finance()
    {
        return $this->belongsTo(Finance::class, 'finance_file_id');
    }
}