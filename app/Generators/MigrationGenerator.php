<?php

namespace App\Generators;

use App\Generators\Migrations\NameParser;
use App\Generators\Migrations\SchemaParser;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class MigrationGenerator
 * @package Prettus\Repository\Generators
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class MigrationGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'migration/plain';


    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path() . '/database/migrations/';
    }


    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . $this->getFileName() . '.php';
    }


    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return '';
    }


    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return '';
    }


    /**
     * Get migration name.
     *
     * @return string
     */
    public function getMigrationName()
    {
        return strtolower($this->name);
    }


    /**
     * Get file name.
     *
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His_') . $this->getMigrationName();
    }


    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {   
        return new SchemaParser($this->fields);
    }


    /**
     * Get name parser.
     *
     * @return NameParser
     */
    public function getNameParser()
    {
        return new NameParser($this->name);
    }

    /**
     * Get database connection.
     *
     * @return string DBConnection
     */
    public function getDBConnection()
    {
        return $this->option('db_connection');
    }

    /**
     * Get database connection.
     *
     * @return string Table Engine
     */

    public function getEngine()
    {
        return $this->option('engine');
    }

    /**
     * Get database connection.
     *
     * @return string Character Set
     */

    public function getCharset()
    {
        return $this->option('charset');
    }

    /**
     * Get database connection.
     *
     * @return string Collation
     */
    public function getCollation()
    {
        return $this->option('collation');
    }

    /**
     * Get database connection.
     *
     * @return Table Comment
     */
    public function getTableComment()
    {
        $parser = $this->getNameParser();
        $comment = trim($this->option('table_comment'));
        return $comment == '' ? '' : 'DB::select("ALTER TABLE '.$parser->getTable().' COMMENT = \''.$comment.'\'");';
    }

    

    /**
     * Get stub templates.
     *
     * @return string
     */
    public function getStub()
    {
        $parser = $this->getNameParser();

        $action = $parser->getAction();
        switch ($action) {
            case 'add':
            case 'append':
            case 'update':
            case 'insert':
                $file = 'change';
                $replacements = [
                    'class'       => $this->getClass(),
                    'table'       => $parser->getTable(),
                    'fields_up'   => $this->getSchemaParser()->up(),
                    'fields_down' => $this->getSchemaParser()->down(),
                ];
                break;

            case 'delete':
            case 'remove':
            case 'alter':
                $file = 'change';
                $replacements = [
                    'class'       => $this->getClass(),
                    'table'       => $parser->getTable(),
                    'fields_down' => $this->getSchemaParser()->up(),
                    'fields_up'   => $this->getSchemaParser()->down(),
                ];
                break;
            default:
                $file = 'create';
                $replacements = [
                    'dbconnection' => $this->getDBConnection(),
                    'class'        => $this->getClass(),
                    'table'        => $parser->getTable(),
                    'engine'       => $this->getEngine(),
                    'charset'      => $this->getCharset(),
                    'collation'    => $this->getCollation(),
                    'tablecomment' => $this->getTableComment(),
                    'fields'       => $this->getSchemaParser()->up(),
                ];
                break;
        }

        $path = config('repository.generator.stubsOverridePath', __DIR__);

        if (!file_exists($path . "/Stubs/migration/{$file}.stub")) {
            $path = __DIR__;
        }

        if (!file_exists($path . "/Stubs/migration/{$file}.stub")) {
            throw new FileNotFoundException($path . "/Stubs/migration/{$file}.stub");
        }

        return Stub::create($path . "/Stubs/migration/{$file}.stub", $replacements);
    }
}
