<?php

namespace App\Models\Core;

use App\Traits\HasMultipleKey;
use GeneaLabs\LaravelModelCaching\CachedModel;

/**
 * Class PlantZone.
 *
 * @package namespace App\Models\Core;
 */
class PlantZone extends CachedModel
{
    use HasMultipleKey;

    protected $table = 'plant_zones';
    protected $primaryKey = ['plant_code', 'zone_id'];
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
