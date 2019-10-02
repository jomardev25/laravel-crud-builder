<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Navigation Menu
    |--------------------------------------------------------------------------
    |
    | This array is for Navigation menus of the backend.  Just add/edit or
    | remove the elements from this array which will automatically change the
    | navigation.
    |
    */

    // SIDEBAR LAYOUT - MENU

    'sidebar' => [
        [
            'title' => 'Dashboard',
            'link' => '#',
            'active' => 'dashboard*',
            'icon' => 'icon-fa icon-fa-dashboard',
        ],
        [
            'title' => 'Modules',
            'link' => '/core/modules',
            'active' => 'core/modules*',
            'icon' => 'icon-fa icon-fa-check',
        ],
        [
            'title' => 'Maintenance',
            'link' => '#',
            'active' => 'maintenance/*',
            'icon' => 'icon-fa icon-fa-cogs',
            'children' => [
                [
                    'title' => 'User Management',
                    'link' => '#',
                    'active' => 'user-management/*',
                    'children' => [
                        [
                            'title' => 'Users',
                            'link' => '/admin/settings/social',
                            'active' => 'admin/settings/social',
                        ],
                        [
                            'title' => 'Roles',
                            'link' => '/admin/settings/social',
                            'active' => 'admin/settings/social',
                        ],
                        [
                            'title' => 'Permissions',
                            'link' => '/admin/settings/social',
                            'active' => 'admin/settings/social',
                        ],
                        [
                            'title' => 'Mail Driver',
                            'link' => 'admin/settings/mail',
                            'active' => 'admin/settings/mail*',
                        ]
                    ]
                ],
                [
                    'title' => 'Category',
                    'link' => '/admin/settings/social',
                    'active' => 'admin/settings/social',
                ],
                [
                    'title' => 'Stores',
                    'link' => '/admin/settings/social',
                    'active' => 'admin/settings/social',
                ],
                [
                    'title' => 'Menu',
                    'link' => '/admin/settings/social',
                    'active' => 'admin/settings/social',
                ],
                [
                    'title' => 'Department',
                    'link' => '/admin/settings/social',
                    'active' => 'admin/settings/social',
                ]
            ]
        ],
    ]
];
