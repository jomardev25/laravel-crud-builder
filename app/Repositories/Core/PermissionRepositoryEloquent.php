<?php

namespace App\Repositories\Core;

use App\Contracts\Core\PermissionRepository;
use App\Models\Core\Permission;

/**
 * Class PermissionRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class PermissionRepositoryEloquent extends BaseRepository implements PermissionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        
    }
    
}
