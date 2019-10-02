<?php

namespace App\Models\Core;

use Awobaz\Compoships\Compoships;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use GeneaLabs\LaravelModelCaching\CachedModel;

/**
 * Class MenuItem.
 *
 * @package namespace App\Models\Core;
 */
class MenuItem extends CachedModel
{
    use SoftDeletes, LogsActivity;

    use Compoships;

    /* protected $table = 'menu_items';
    protected $primaryKey = 'menu_id'; */

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    public $incrementing = false;

    /* protected static $logName = '';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['created_at','updated_at'];
    protected static $logOnlyDirty = true; */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'account_num'];

    public function posts()
    {
        return $this->hasMany('App\Models\Core\Menu', ['customer_id', 'account_num'], ['customer_id', 'account_num']);
    }

    /* public function rootMenu()
	{
		return $this->belongsTo('App\Models\Core\Menu', 'menu_id')->withDefault([
            'menu_id' => 0,
            'name' => 'No Parent Menu'
        ]);
    }

    public function route()
    {
        return $this->hasOne('App\Models\Core\Route', 'route_id', 'route_id')->withDefault([
            'route_id' => 0,
            'link' => 'javascript:void(0);'
        ]);
    } */

}
