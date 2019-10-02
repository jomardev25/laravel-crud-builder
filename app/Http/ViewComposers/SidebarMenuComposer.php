<?php


namespace App\Http\ViewComposers;

use App\Contracts\Admin\MenuItemRepository;
use Illuminate\View\View;
use App\Contracts\Admin\MenuRepository;
use App\Contracts\Admin\UserRepository;

class SidebarMenuComposer
{
    private $menuRepo;
    private $menuItemRepo;
    private $userRepo;

    public function __construct(MenuRepository $menuRepo, UserRepository $userRepo, MenuItemRepository $menuItemRepo)
    {
        $this->menuRepo = $menuRepo;
        $this->menuItemRepo = $menuItemRepo;
        $this->userRepo = $userRepo;
    }
    
    public function compose(View $view)
    {
        $menuId = $this->menuRepo->findByField('name', "Sidebar", ['menu_id'])->first()->menu_id;
        $menuItems = $this->userRepo->getUserMenu($menuId, $this->menuItemRepo);
        $sidebar = tree($menuItems['parents'], $menuItems['children']);
        $view->with('sidebar', $sidebar);
    }
}