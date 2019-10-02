<?php

namespace App\Repositories\Core;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\Core\RouteRepository;
use App\Models\Core\Route;

/**
 * Class RouteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RouteRepositoryEloquent extends BaseRepository implements RouteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Route::class;
    }

    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }
    
}
