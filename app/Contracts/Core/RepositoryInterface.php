<?php
namespace App\Contracts\Core;

/**
 * Interface RepositoryInterface
 * @package App\Contracts
 */
interface RepositoryInterface
{
    /**
    * @return current model
    */
    public function getModel();

    /**
     * Retrieve deleted items
     * @return deleted items
     */
    public function onlyTrashed();

    /**
     * Retrieve items including deleted
     * @return deleted items
     */
    public function withTrashed();

    /**
    * Get all items by status ()
    *
    * @param $status array
    * @param $columns array
    * @return all items (active/inactive)
    */
    public function getAllByStatus(array $status, array $columns = ['*']);

     /**
     * Finds one item by the provided field.
     *
     * @param string $field Field on the database that you will filter by.
     * @param $value mixed Value used for the filter. If NULL passed then it will take ONLY the criteria.
     * @param array $columns Columns to retrieve with the object.
     * @return mixed Model|NULL An Eloquent object when there is a result, NULL when there are no matches.
     */
    public function findOneBy(string $field, $value = NULL, array $columns = ['*'] );
}