<?php namespace App\Models\Modules\Maintenance;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;


class Plant extends Model
{

	protected $table = 'plants';
	protected $primaryKey = 'plant_code';	

}
