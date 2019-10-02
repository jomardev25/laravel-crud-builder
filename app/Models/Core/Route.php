<?php

namespace App\Models\Core;

use GeneaLabs\LaravelModelCaching\CachedModel;

/**
 * Class Route.
 *
 * @package namespace App\Models;
 */
class Route extends CachedModel 
{
    protected $table = 'routes';
    protected $primaryKey = 'route_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'method', 'link', 'param', 'action', 'permission_id', 'middleware', 'description'];



}
