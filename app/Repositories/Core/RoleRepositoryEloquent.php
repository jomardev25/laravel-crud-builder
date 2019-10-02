<?php

namespace App\Repositories\Core;


use App\Contracts\Core\RoleRepository;
use App\Models\Core\Role;

/**
 * Class RoleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class RoleRepositoryEloquent extends BaseRepository implements RoleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        
    }
    
}
