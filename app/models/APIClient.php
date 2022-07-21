<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class APIClient extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'api_clients';

    protected $fillable = [
        'name',
        'secret',
        'expiry',
        'status',
    ];

    /**
     * Get all of the buildings for the APIClient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildings()
    {
        return $this->hasMany(APIBuilding::class, 'client_id');
    }
}