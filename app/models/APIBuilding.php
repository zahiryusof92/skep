<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class APIBuilding extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'api_buildings';

    protected $fillable = [
        'client_id',
        'file_id',
        'strata_id',
        'status',
    ];

    public function scopeSelf(Builder $builder) {
        $builder = $builder->join('api_clients', 'api_buildings.client_id', '=', 'api_clients.id')
                           ->join('files', 'api.buildings.file_id', '=', 'files.id');
        
        return $builder;
    }

    /**
     * Get the client that owns the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(APIClient::class, 'client_id');
    }
    
    /**
     * Get the file that owns the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(Files::class, 'file_id');
    }
    
    /**
     * Get the strata that owns the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function strata()
    {
        return $this->belongsTo(Strata::class, 'strata_id');
    }

    /**
     * Get all of the logs for the APIBuilding
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(APIBuildingLog::class, 'api_building_id');
    }
}