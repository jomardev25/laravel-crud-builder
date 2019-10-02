<?php

namespace App\Models\Core;

use Awobaz\Compoships\Compoships;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use GeneaLabs\LaravelModelCaching\CachedModel;

/**
 * Class Menu.
 *
 * @package namespace App\Models\Core;
 */
class Menu extends CachedModel
{
    use SoftDeletes, LogsActivity;

    use Compoships;
    
    protected $table = 'posts';
    protected $primaryKey = 'id';

    protected static $logName = 'menus';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

}
