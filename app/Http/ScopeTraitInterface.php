<?php

namespace PHIWeb\Http\Traits;

//app/Http/Traits/ScopeTraitInterface.php

interface ScopeTraitInterface
{
    /**
     * Get the fields from the query string
     * @return array|null
     */
    protected function getFieldsArray();
    /**
     * Get the where conditions from the query string
     * @return array
     */
    protected function getConditionsArray();

    /**
     * Get the Items per page from the query string
     * @return int|null
     */
    protected function getItemsPerPage();
    /**
     * Get the sort by field from the query string
     * @return mixed
     */
    protected function getSortBy();
    /**
     * Get the sort direction from the query string
     * Default asc
     * @return string
     */
    protected function getSortDirection();
    /**
     * Get the objects from the query string
     * The final response will the instance(s) that belong to the current instance
     * @return array|null
     * @TODO:
     * $string not defined
     */
    protected function getWithArray();

    protected function getWithPaginate();
}