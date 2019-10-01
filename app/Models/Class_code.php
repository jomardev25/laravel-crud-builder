<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class class_code extends Sximo  {
	
	protected $table = 'class_codes';
	protected $primaryKey = '{key}';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  ";
	}	

	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
