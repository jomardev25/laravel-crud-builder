<?php

namespace App\Repositories\Core;

use App\Contracts\Core\MenuItemRepository;
use App\Models\Core\MenuItem;

/**
 * Class MenuItemRepositoryEloquent.
 *
 * @package namespace App\Repositories\Core;
 */
class MenuItemRepositoryEloquent extends BaseRepository implements MenuItemRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MenuItem::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }
    
}
