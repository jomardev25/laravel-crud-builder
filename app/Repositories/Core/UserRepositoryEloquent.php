<?php

namespace App\Repositories\Core;

use App\Contracts\Core\UserRepository;
use App\User;


/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        
    }
    
}
