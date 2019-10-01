<?php

namespace App\Models\Core;


use Illuminate\Database\Eloquent\SoftDeletes;
use GeneaLabs\LaravelModelCaching\CachedModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class Module.
 *
 * @package namespace App\Models;
 */
class Module extends CachedModel implements Transformable
{
    use SoftDeletes, TransformableTrait;

    protected $table = 'modules';
    protected $primaryKey = 'module_id';
    protected $guard = ['module_id'];
    protected $fillable = ['name', 'title', 'description', 'model', 'controller', 'db_connection', 'table', 'table_schema_id', 'primary_key', 'template_id', 'config'];

}