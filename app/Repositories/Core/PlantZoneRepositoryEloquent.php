<?php

namespace App\Repositories\Core;

use App\Contracts\Core\PlantZoneRepository;
use App\Models\Core\PlantZone;

/**
 * Class PlantZoneRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class PlantZoneRepositoryEloquent extends BaseRepository implements PlantZoneRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlantZone::class;
    }    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }
    
}
