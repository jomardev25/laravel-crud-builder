<?php

namespace App\Repositories\Core;
use Closure;
use Exception;
use App\Traits\CacheableTrait;
use App\Traits\InsertOnDuplicateKey;
use App\Contracts\Core\RepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;

abstract class BaseRepository extends PrettusBaseRepository implements RepositoryInterface
{

    use InsertOnDuplicateKey, CacheableTrait;

    /**
     * The relations to eager load on query execution.
     *
     * @var array
     */
    protected $relations = [];
    /**
     * The query where clauses.
     *
     * @var array
     */
    protected $where = [];
    /**
     * The query whereIn clauses.
     *
     * @var array
     */
    protected $whereIn = [];
    /**
     * The query whereNotIn clauses.
     *
     * @var array
     */
    protected $whereNotIn = [];
    /**
     * The query whereHas clauses.
     *
     * @var array
     */
    protected $whereHas = [];
    /**
     * The query scopes.
     *
     * @var array
     */
    protected $scopes = [];
    /**
     * The "offset" value of the query.
     *
     * @var int
     */
    protected $offset;
    /**
     * The "limit" value of the query.
     *
     * @var int
     */
    protected $limit;
    /**
     * The column to order results by.
     *
     * @var array
     */
    protected $orderBy = [];
    /**
     * The column to order results by.
     *
     * @var array
     */
    protected $groupBy = [];
    /**
     * The query having clauses.
     *
     * @var array
     */
    protected $having = [];

    /**
     * Only trashed data
     *
     * @var $this
     */
    protected $onlyTrashed;

    /**
     * Data with trashed
     *
     * @var $this
     */
    protected $withTrashed;

    /**
     * Reset repository to its defaults.
     *
     * @return $this
     */
    protected function resetRepository()
    {
        $this->relations = [];
        $this->where = [];
        $this->whereIn = [];
        $this->whereNotIn = [];
        $this->whereHas = [];
        $this->scopes = [];
        $this->offset = null;
        $this->limit = null;
        $this->orderBy = [];
        $this->groupBy = [];
        $this->having = [];
        $this->onlyTrashed = null;
        $this->withTrashed = null;
        return $this;
    }

    /**
    * @param $name of property or method
    * @return inaccessible property or method
    */
    public function __get($name)
    {
        if(property_exists($this, $name))
            return $this->{$name};
        elseif(property_exists($this, $name))
            return $this->$name();
        else
            throw new Exception("Property or method $name does not exists", 500);
    }

    /** 
    * Get the curent model
    * @return current model
    */
    public function getModel()
    {
        return $this->model;
    }

    public function getConnection()
    {
        return $this->model->getConnection();
    }
    
    /**
     * Get the table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->model->getTable();
    }

    /**
     * Get the table prefix.
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->model->getConnection()->getTablePrefix();
    }

    /**
     * Get the primary key.
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->model->getKeyName();
    }

    /**
     * Set IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     * @return IoC container instance
     */
    public function getContainer($service = null)
    {
        return is_null($service) ? ($this->container ?: app()) : ($this->container[$service] ?: app($service));
    }

    /**
     * Retrieve deleted items
     * @return deleted items
     */
    public function onlyTrashed()
    {
        $this->onlyTrashed = true;
        return $this;
    }

    /**
     * Retrieve items including deleted
     * @return deleted items
     */
    public function withTrashed()
    {
        $this->withTrashed = true;
        return $this;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }
        $this->relations = $relations;
        return $this;
    }

    public function where($attribute, $operator = null, $value = null, $boolean = 'and')
    {
        $this->where[] = [$attribute, $operator, $value, $boolean ?: 'and'];
        return $this;
    }

    public function whereIn($attribute, $values, $boolean = 'and', $not = false)
    {
        $this->whereIn[] = [$attribute, $values, $boolean ?: 'and', (bool) $not];
        return $this;
    }

    public function whereNotIn($attribute, $values, $boolean = 'and')
    {
        $this->whereNotIn[] = [$attribute, $values, $boolean ?: 'and'];
        return $this;
    }

    public function whereHasRelation($relation, Closure $callback = null, $operator = '>=', $count = 1)
    {
        $this->whereHas[] = [$relation, $callback, $operator ?: '>=', $count ?: 1];
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy($attribute, $direction = 'asc')
    {
        $this->orderBy[] = [$attribute, $direction ?: 'asc'];
        return $this;
    }

    public function get($attributes = ['*'])
    {
        $result = $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($attributes) {
            return $this->prepareQuery($this->model)->get($attributes);
        });

        return $result;
    }

    public function groupBy($column)
    {
        $this->groupBy = array_merge((array) $this->groupBy, is_array($column) ? $column : [$column]);
        return $this;
    }

    public function having($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->having[] = [$column, $operator, $value, $boolean ?: 'and'];
        return $this;
    }

    public function orHaving($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->having($column, $operator, $value, 'or');
    }

    public function saveOrUpdate($id, array $attributes = [], bool $syncRelations = false)
    {
        return ! $id ? $this->create($attributes, $syncRelations) : $this->update($id, $attributes, $syncRelations);
    }

    /**
    * Get all items by status
    *
    * @param $status array
    * @param $columns array
    * @return all items (active/inactive)
    */
    public function getAllByStatus(array $status, array $columns = ["*"])
    {
        if(count($status) == 1 && $status[0] == 0){
            $results = $this->onlyTrashed()->get($columns);
        }elseif(count($status) == 1 && $status[0] == 1){
            $results = $this->get($columns);
        }else{
            $results = $this->withTrashed()->get($columns);
        }

        return $results;
    }

    public function findOneBy(string $field, $value = NULL, array $columns = ["*"])
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->where($field, $value)->limit(1)->get($columns);

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function findFirst($attributes = ['*'])
    {
        $result = $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($attributes) {
            return $this->prepareQuery($this->model)->first($attributes);
        });

        return $result;
    }

    public function findOrFail($id, $attributes = ['*'])
    {
        $result = $this->find($id, $attributes);
        if(is_array($id)) {
            if (count($result) === count(array_unique($id))) {
                return $result;
            }
        }elseif(! is_null($result)) {
            return $result;
        }
        throw new EntityNotFoundException($this->getModel(), $id);
    }

    public function findOrNew($id, $attributes = ['*'])
    {
        if (!is_null($entity = $this->find($id, $attributes))) {
            return $entity;
        }
        return $this->makeModel();
    }

    public function paginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        return $this->executeCallback(static::class, __FUNCTION__, array_merge(func_get_args(), compact('page')), function () use ($perPage, $attributes, $pageName, $page) {
            return $this->prepareQuery($this->model)->paginate($perPage, $attributes, $pageName, $page);
        });
    }

    public function simplePaginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        return $this->executeCallback(static::class, __FUNCTION__, array_merge(func_get_args(), compact('page')), function () use ($perPage, $attributes, $pageName, $page) {
            return $this->prepareQuery($this->model)->simplePaginate($perPage, $attributes, $pageName, $page);
        });
    }

    public function whereCount($columns = '*'): int
    {
        return $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($columns) {
            return $this->prepareQuery($this->model)->count($columns);
        });
    }

    public function min($column)
    {
        return $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($column) {
            return $this->prepareQuery($this->model)->min($column);
        });
    }

    public function max($column)
    {
        return $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($column) {
            return $this->prepareQuery($this->model)->max($column);
        });
    }

    public function avg($column)
    {
        return $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($column) {
            return $this->prepareQuery($this->model)->avg($column);
        });
    }

    public function sum($column)
    {
        return $this->executeCallback(static::class, __FUNCTION__, func_get_args(), function () use ($column) {
            return $this->prepareQuery($this->model)->sum($column);
        });
    }

    public function beginTransaction(): void
    {
        $this->getContainer('db')->beginTransaction();
    }

    public function commit(): void
    {
        $this->getContainer('db')->commit();
    }
 
    public function rollBack(): void
    {
        $this->getContainer('db')->rollBack();
    }

    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([new static(), $method], $parameters);
    }

    public function __call($method, $parameters)
    {
        if (method_exists($model = $this->model, $scope = 'scope'.ucfirst($method))) {
            $this->scope($method, $parameters);
            return $this;
        }
        return call_user_func_array([$this->model, $method], $parameters);
    }

    protected function executeCallback($class, $method, $args, Closure $closure)
    {
        $result = call_user_func($closure);
        $this->resetRepository();
        return $this->parserResult($result);
    }

     /**
     * Prepare query.
     *
     * @param object $model
     *
     * @return mixed
     */
    protected function prepareQuery($model)
    {
        // Set the relationships that should be eager loaded
        if (!empty($this->relations)) {
            $model = $model->with($this->relations);
        }
        // Add a basic where clause to the query
        foreach ($this->where as $where) {
            list($attribute, $operator, $value, $boolean) = array_pad($where, 4, null);
            $model = $model->where($attribute, $operator, $value, $boolean);
        }
        // Add a "where in" clause to the query
        foreach ($this->whereIn as $whereIn) {
            list($attribute, $values, $boolean, $not) = array_pad($whereIn, 4, null);
            $model = $model->whereIn($attribute, $values, $boolean, $not);
        }
        // Add a "where not in" clause to the query
        foreach ($this->whereNotIn as $whereNotIn) {
            list($attribute, $values, $boolean) = array_pad($whereNotIn, 3, null);
            $model = $model->whereNotIn($attribute, $values, $boolean);
        }
        // Add a "where has" clause to the query
        foreach ($this->whereHas as $whereHas) {
            list($relation, $callback, $operator, $count) = array_pad($whereHas, 4, null);
            $model = $model->whereHas($relation, $callback, $operator, $count);
        }
        // Add a "scope" to the query
        foreach ($this->scopes as $scope => $parameters) {
            $model = $model->{$scope}(...$parameters);
        }
        // Set the "offset" value of the query
        if ($this->offset > 0) {
            $model = $model->offset($this->offset);
        }
        // Set the "limit" value of the query
        if ($this->limit > 0) {
            $model = $model->limit($this->limit);
        }
        // Add an "order by" clause to the query.
        foreach ($this->orderBy as $orderBy) {
            list($attribute, $direction) = $orderBy;
            $model = $model->orderBy($attribute, $direction);
        }
        // Add an "group by" clause to the query.
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $group) {
                $model = $model->groupBy($group);
            }
        }
        // Add a "having" clause to the query
        foreach ($this->having as $having) {
            list($column, $operator, $value, $boolean) = array_pad($having, 4, null);
            $model = $model->having($column, $operator, $value, $boolean);
        }

        if($this->onlyTrashed){
            $model = $model->onlyTrashed();
        }

        if($this->withTrashed){
            $model = $model->withTrashed();
        }

        return $model;
    }
}