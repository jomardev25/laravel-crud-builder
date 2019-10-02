<?php

namespace App\Repositories\Core;

use App\Models\Core\Module;
use App\Traits\ModuleTrait;
use Illuminate\Http\Request;
use App\Helpers\ModuleHelper;
use Illuminate\Support\Facades\DB;
use App\Generators\MigrationGenerator;
use App\Contracts\Core\ModuleRepository;

/**
 * Class ModuleRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ModuleRepositoryEloquent extends BaseRepository implements ModuleRepository
{
    use ModuleTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Module::class;
    }

    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
       
    }

    public function store(Request $request)
    {
        if($request->table_schema == 1){
            $table = $request->db_connection_existing_table;
            $dbConnection = $request->db_connection_existing_table;
        }else{
            $table = $request->table !='' ? $request->table : snake_case(str_plural($request->name));
            $dbConnection = $request->db_connection;
        }
        
        /* $migrationGenerator = new MigrationGenerator([
            'name'          => 'create_' . $table . '_table',
            'db_connection' => $request->db_connection,
            'engine'        => $request->engine,
            'charset'       => $request->charset,
            'collation'     => $request->collation,
            'table_comment' => $request->table_comment,
            'fields'        => [
                'field_name' => $request->field_name,
                'db_type'    => $request->db_type,
                'length'     => $request->length,
                'decimals'   => $request->decimals,
                'default'    => $request->user_defined,
                'not_null'   => $request->not_null,
                'index'      => $request->index
            ],
            'force'         => '--f'
        ]); */

        $primary = $this->findPrimaryKey($table);
        $columns = \DB::select("SHOW COLUMNS FROM ".$table);
        $select =  " SELECT {$table}.* FROM {$table} ";
        $where = " WHERE ".$table.".".$primary." IS NOT NULL";
        if($primary !='') {
            $where = " WHERE ".$table.".".$primary." IS NOT NULL";
        }else{ 
            $where ='' ;
        }  

        $i = 0; $rowGrid = []; $rowForm = [];

        foreach($columns as $column){

            if(!isset($column->Table)) $column->Table = $table;
            if($column->Key =='PRI') $column->Type ='hidden';
            if($column->Table == $table){
                $formCreator = $this->configForm($column->Field, $column->Table, $column->Type, $i);
                $relation = $this->buildRelation($table ,$column->Field);
                
                foreach($relation as $row){
                    $array = ['external',$row['table'],$row['column']];
                    $formCreator = $this->configForm($column->Field,$table,'select',$i,$array);       
                }

                $rowForm[] = $formCreator;
            }    
            
            $rowGrid[] = $this->configGrid($column->Field,$column->Table,$column->Type,$i);                
            $i++;
        }   

        $config['sql_select']   = "";
        $config['sql_where']    = "";
        $config['sql_group']    = "";
        $config['table_db']     = $table;
        $config['primary_key']  = $primary;
        $config['grid']         = $rowGrid ;
        $config['forms']        = $rowForm ; 
    
        $data = [
            'name'              => $request->name,
            'title'             => $request->title,
            'description'       => $request->description,
            'model'             => 'ClassCode',
            'controller'        => 'ClassCodeController',
            'db_connection'     => $dbConnection, 
            'table'             => $table,  
            'table_schema_id'   => 1,    
            'primary_key'       => $primary,
            'template_id'       => 1,
            'config'            => $this->jsonEncode($config),            
        ];
    
        $this->create($data);
    } 

    public function build($module_id)
    {
        $module = $this->find($module_id);  
        
        $config = ModuleHelper::jsonDecode($module->config); 
        $class = $module->name;
        $ctr = ucwords($module->name);
        $path = $module->name;
        $f = '';
        $req = '';
        $codes = [
            'controller' => ucwords($class),
            'class'      => $class,
            'fields'     => $f,
            'required'   => $req,
            'table'      => $module->table ,
            'title'      => $module->title ,
            'sql_select' => $config['sql_select'],
            'sql_where'  => $config['sql_where'],
            'sql_group'  => $config['sql_group'],
        ]; 

        if(!isset($config['form_layout'])){
            $config['form_layout'] = [
                'column'  => 1,'title' => $module->module_title,
                'format'  =>'grid',
                'display' =>'horizontal'
            ];
        }               
            
        $codes['form_javascript'] = ModuleHelper::toJavascript($config['forms'],$path,$class);
        $codes['form_entry'] = ModuleHelper::toForm($config['forms'],$config['form_layout']);
        $codes['form_display'] = isset($config['form_layout']['display']) 
                                 ? $config['form_layout']['display'] : 'horizontal';    

        $codes['form_view'] = ModuleHelper::toView($config['grid']);
       
        $codes['masterdetailmodel']  = '';
        $codes['masterdetailinfo']   = '';
        $codes['masterdetailgrid']   = '';
        $codes['masterdetailsave']   = '';
        $codes['masterdetailform']   = '';
        $codes['masterdetailsubform']   = '';
        $codes['masterdetailview']   = '';
        $codes['masterdetailjs']   = '';
        $codes['masterdetaildelete']   = '';

        if(isset($config['subform'])){
            $md = ModuleHelper::toMasterDetail($config['subform']);    
            $codes['masterdetailmodel']  = $md['masterdetailmodel'] ; 
            $codes['masterdetailinfo']   = $md['masterdetailinfo'] ;   
            $codes['masterdetailsave']   = $md['masterdetailsave'] ;
            $codes['masterdetailsubform']   = $md['masterdetailsubform'] ;  
            $codes['masterdetailform']   = $md['masterdetailform'] ;                
            $codes['masterdetaildelete'] = $md['masterdetaildelete'];   
            $codes['masterdetailjs']     = $md['masterdetailjs'] ;
        }

        $dir = base_path().'/resources/views/modules/'.$class; 
        $dirPublic = base_path().'/resources/views/modules/'.$class.'/public'; 
        $dirC = app_path().'/Http/Controllers/';
        $dirM = app_path().'/Models/';
        
        if(!is_dir($dir)) 
            mkdir( $dir, 0777);  
        if(!is_dir($dirPublic))
            mkdir( $dirPublic, 0777);  
            
        
 
        if($module->template_id == 1){

            $template = base_path().'/app/generators/stubs/template/native/';
           
            $controller = file_get_contents(  $template.'controller.tpl' );
            $grid = file_get_contents(  $template.'grid.tpl' );               
            $view = file_get_contents(  $template.'view.tpl' );
            $form = file_get_contents(  $template.'form.tpl' );
            $model = file_get_contents(  $template.'model.tpl' );
            $front = file_get_contents(  $template.'frontend.tpl' );
            $frontview = file_get_contents(  $template.'frontendview.tpl' ); 
            $frontform = file_get_contents(  $template.'frontform.tpl' );

            if(isset($config['subgrid']) && count($config['subgrid'])>=1)
            {
                $view = file_get_contents(  $template.'view_detail.tpl' );
            } else {
                $view = file_get_contents(  $template.'view.tpl' );
            }

    
            $build_controller       = ModuleHelper::blend($controller,$codes);    
            $build_view             = ModuleHelper::blend($view,$codes);    
            $build_form             = ModuleHelper::blend($form,$codes);    
            $build_grid             = ModuleHelper::blend($grid,$codes);    
            $build_model            = ModuleHelper::blend($model,$codes);    
            $build_front            = ModuleHelper::blend($front,$codes);   
            $build_frontview        = ModuleHelper::blend($frontview,$codes);   
            $build_frontform        = ModuleHelper::blend($frontform,$codes); 

            file_put_contents(  $dirC ."{$ctr}Controller.php" , $build_controller) ;    
            file_put_contents(  $dirM ."{$ctr}.php" , $build_model) ;
            file_put_contents(  $dir."/index.blade.php" , $build_grid) ; 
            file_put_contents( $dir."/form.blade.php" , $build_form) ;

            file_put_contents(  $dir."/view.blade.php" , $build_view) ;       
            file_put_contents(  $dir."/public/index.blade.php" , $build_front) ;  
            file_put_contents(  $dir."/public/view.blade.php" , $build_frontview) ; 
            file_put_contents(  $dir."/public/form.blade.php" , $build_frontform) ;
        }
    }
    
    public function getTableList(string $connection)
    {
        $tables = DB::connection($connection)->select('SHOW TABLES');
        $tables = array_map('current',$tables);
        return $tables;
    }
}
