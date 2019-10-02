<?php

namespace App\Repositories\Core;

use App\Contracts\Core\MenuRepository;
use App\Models\Core\Menu;

/**
 * Class MenuRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class MenuRepositoryEloquent extends BaseRepository implements MenuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Menu::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }
    
}
