<?php

namespace App\Traits;

trait ModuleTrait
{
    public function findPrimaryKey($table)
    {
        $sql= "SHOW columns FROM `{$table}` WHERE `Key` LIKE '%PRI%'";
        $primaryKey = '';
        foreach(\DB::select($sql) as $key)
        {
            $primaryKey = $key->Field;
        }
        return $primaryKey; 
    }

    public function configGrid($field, $alias, $type, $sort)
    {
        $grid = [ 
            "field"     => $field,
            "alias"     => $alias,
            "label"     => ucwords(str_replace('_',' ',$field)),
            "language"    => array(),
            "search"     => '1' ,
            "download"     => '1' ,
            "align"     => 'left' ,
            "view"         => '1' ,
            "detail"      => '1',
            "sortable"     => '1',
            "frozen"     => '0',
            'hidden'    => '0',            
            "sortlist"     => $sort ,
            "width"     => '100',
            "conn"          => ['valid'=>'0','db'=>'','key'=>'','display'=>''],
            "format_as"     =>'',
            "format_value"  =>'',            

        ];     
        return $grid;
    }

    public function configForm($field, $alias, $type, $sort, $opt = [])
    {
		$optType = ''; $lookupTable =''; $lookupKey ='';
        if(count($opt) >=1) {
            $optType = $opt[0]; $lookupTable = $opt[1]; $lookupKey = $opt[2];
		}
		           
        $forms = [
            "field"     	=> $field,
            "alias"     	=> $alias,
            "label"     	=> ucwords(str_replace('_',' ',$field)),
            "language"    	=> [],
            'required'      => '0',
            'view'          => '1',
            'type'          => $this->configFieldType($type),
            'add'           => '1',
            'edit'          => '1',
            'search'        => '1',
            'size'          => 'span12',
            "sortlist"     	=> $sort ,
            'form_group'    => '',
            'option'        => [
                    "opt_type"              => $optType,
                    "lookup_query"          => '',
                    "lookup_table"          => $lookupTable,
                    "lookup_key"            => $lookupKey,
                    "lookup_value"          => $lookupKey,
                    'is_dependency'         => '',
                    'select_multiple'       => '0',
                    'image_multiple'        => '0',
                    'lookup_dependency_key' => '',
                    'path_to_upload'        => '',
                    'upload_type'           => '',
                    'tooltip'               => '',
                    'attribute'             => '',
                    'extend_class'          => '',
                    'prefix'                => '',
                    'sufix'                 => ''
                ]
            ];
        return $forms;
    }

    public function buildRelation($table, $field)
    {
		$pdo = \DB::getPdo();
		$db = "db_admin";
        $sql = "
        SELECT
            referenced_table_name AS 'table',
            referenced_column_name AS 'column'
        FROM
            information_schema.key_column_usage
        WHERE
            referenced_table_name IS NOT NULL
            AND table_schema = '".$db."'  AND table_name = '{$table}' AND column_name = '{$field}' ";
        $result = $pdo->query($sql);
        $rows = array();
        while ($row =  $result->fetch()) {
            $rows[] = $row;
        } 
        return $rows;  
    } 

    public function configFieldType($type)
    {
        switch($type)
        {
            default: $type = 'text'; break;
            case 'timestamp'; $type = 'text_datetime'; break;
            case 'datetime'; $type = 'text_datetime'; break;
			case 'string'; $type = 'text'; break;
			case 'char'; $type = 'text'; break;
            case 'int'; $type = 'text'; break;
            case 'text'; $type = 'textarea'; break;
            case 'blob'; $type = 'textarea'; break;
            case 'select'; $type = 'select'; break;
        }
        return $type;
    }
    
    public function _sort($a, $b) 
    {
        if ($a['sortlist'] == $a['sortlist']){
		    return strnatcmp($a['sortlist'], $b['sortlist']);
		}
		return strnatcmp($a['sortlist'], $b['sortlist']);
    }
        
    public function blend($str,$data) 
    {
		$src = $rep = array();
		
		foreach($data as $k=>$v){
			$src[] = "{".$k."}";
			$rep[] = $v;
		}
		
		if(is_array($str)){
			foreach($str as $st ){
				$res[] = trim(str_ireplace($src,$rep,$st));
			}
		} else {
			$res = str_ireplace($src,$rep,$str);
		}
		
		return $res;		
    }

}