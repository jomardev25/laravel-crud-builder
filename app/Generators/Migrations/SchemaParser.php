<?php
namespace App\Generators\Migrations;

/**
 * Class SchemaParser
 * @package Prettus\Repository\Generators\Migrations
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class SchemaParser
{
    /**
     * The array of custom attributes.
     *
     * @var array
     */
    protected $customAttributes = [
        'remember_token' => 'rememberToken()',
        'soft_delete'    => 'softDeletes()',
    ];
    /**
     * The migration schema.
     *
     * @var string
     */
    protected $schema;

    protected $index = [];

    /**
     * Create new instance.
     *
     * @param string|null $schema
     */
    public function __construct($schema = null)
    {
        $this->schema = $schema;
    }

    /**
     * Render up migration fields.
     *
     * @return string
     */
    public function up()
    {
        return $this->render();
    }

    /**
     * Render the migration to formatted script.
     *
     * @return string
     */
    public function render()
    {
        $results = $this->parse($this->schema);
        return $results;
    }

    /**
     * Parse a string to array of formatted schema.
     *
     * @param  string $schema
     *
     * @return array
     */
    public function parse($schema)
    { 
        $i = 0;
        $columnArray = [];
        $indexArray = [];
        $primaryKeys = "primary([";
        $uniqueKeys = "unique([";
        $indexKeys = "index([";
        $spatialKeys = "spatialIndex([";

        foreach ($this->schema['field_name'] as $field) {
            $column = '';
            $dbType = $this->schema['db_type'][$i];
            $length = $this->schema['length'][$i];
            $decimals = $this->schema['decimals'][$i];            
            $dbLength = $this->getFieldWithLength($field, $dbType, $length, $decimals);
            $default = $this->schema['default'][$i];
            $index = $this->schema['index'][$i];
            if($dbType == 'enum' || $dbType == 'set'){
                $column = $this->getEnumSetColumn($dbType, $length, $default);
            }else{
                $column = $dbType."('".$field."'".$dbLength.")";
                if($default != 'None')
                    $column .= "->default('".$default."')";
                if(!isset($this->schema['not_null'][$i]))
                    $column .= '->nullable()';

                $column .= ';';
            }

            if($index != 'none'){
                if($index == 'primary'){
                    $primaryKeys .= "'".$field."',";
                }elseif($index == 'unique'){
                    $uniqueKeys .= "'".$field."',";
                }elseif($index == 'index'){
                    $indexKeys .= "'".$field."',";
                }elseif($index == 'index'){
                    $spatialKeys .= "'".$field."',";
                }               
            }

            array_push($columnArray, $column);

            $i++;
        }

        if($primaryKeys !="primary(["){
            $primaryKeys = rtrim($primaryKeys, ',').']);'; 
            array_push($indexArray, $primaryKeys);
        }           
        
        if($uniqueKeys !="unique(["){
            $uniqueKeys = rtrim($uniqueKeys, ',').']);'; 
            array_push($indexArray, $uniqueKeys);
        }

        if($indexKeys !="index(["){
            $indexKeys = rtrim($indexKeys, ',').']);'; 
            array_push($indexArray, $indexKeys);
        }

        if($spatialKeys !="spatialIndex(["){
            $spatialKeys = rtrim($spatialKeys, ',').']);'; 
            array_push($indexArray, $spatialKeys);
        }
        
        return $this->createField($columnArray, $indexArray);
    }

    /**
     * Get array of schema.
     *
     * @return array
     */
    public function getSchemas()
    {
        if (is_null($this->schema)) {
            return [];
        }
        return $this->schema;
    }

    public function getEnumSetColumn($field, $dbType, $values, $default)
    {
        $values = explode(',', $values);
        $values = implode("'", $values)."',";
        $column = $dbType."('".$field."',[".$values."])";
        if($default)
            $column .= 'default('.$default.');';
        else
            $column .= ';';
        return $column;
    }

    public function getFieldWithLength($dbType, $length, $decimals)
    {
        $dbTypeLenth = '';
        switch ($dbType) {
            case 'char':
            case 'string':
                if($length !="")
                    $dbTypeLenth = ','.$length;
                break;
            case 'decimal':
            case 'double':
            case 'float': 
            case 'unsignedDecimal':
                if($length && $decimals)
                    $dbTypeLenth = ','.$length.','.$decimals;
                break;
            
            default:
                    $dbTypeLenth = '';
                break;
        }

        return $dbTypeLenth;
    }

    /**
     * Create field.
     *
     * @param  string $column
     * @param  array  $attributes
     * @param  integer $length
     * @return string
     */
    public function createField($columns, $index, $type = 'add')
    {
        $results = ('$table->'.implode(PHP_EOL."\t\t\t".'$table->', $columns).PHP_EOL."\t\t\t");
        $results .= trim('$table->'.implode(PHP_EOL."\t\t\t".'$table->', $index).PHP_EOL."\t\t\t");
        return $results;
    }

    /**
     * Render down migration fields.
     *
     * @return string
     */
    public function down()
    {
        $results = '';
        foreach ($this->toArray() as $column => $attributes) {
            $results .= $this->createField($column, $attributes, 'remove');
        }

        return $results;
    }

    /**
     * Format field to script.
     *
     * @param  int    $key
     * @param  string $field
     * @param  string $column
     *
     * @return string
     */
    protected function addColumn($key, $field, $column, $length = null)
    {  
        if ($this->hasCustomAttribute($column)) {
            return '->' . $field;
        }
        if ($key == 0) {
            if($length){
                return '->' . $column . "('" . $field. "', $length)";    
            }
            return '->' . $column . "('" . $field. "')";
        }
        if (str_contains($field, '(')) {
            return '->' . $field;
        }

        return '->' . $field . '()';
    }

    /**
     * Format field to script.
     *
     * @param  int    $key
     * @param  string $field
     * @param  string $column
     *
     * @return string
     */
    protected function removeColumn($key, $field, $column)
    {
        if ($this->hasCustomAttribute($column)) {
            return '->' . $field;
        }

        return '->dropColumn(' . "'" . $column . "')";
    }
}
