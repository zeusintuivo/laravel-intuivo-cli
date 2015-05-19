<?php namespace PHIWeb\Models\Traits;



interface ModelQueriesInterface
{

    /**
     * Scope to filter the results building a query
     *
     * @param $queryBuilder
     * @param array $conditions
     *
     * @return mixed
     * @author miguel <miguel@gammapartners.com>
     */
    protected function filterConditions($queryBuilder, $conditions = [ ], $column, $dir);


    /**
     * Apply where's to the Query Builder
     *
     * @param $query
     * @param array $conditions
     * @param $orderByColumn
     * @param $dir
     *
     * @return mixed
     *
     * @author miguel <miguel@gammapartners.com>
     */
    public static function getFilterQuery($query, $conditions = [ ], $orderByColumn = 'created_at', $dir = 'asc', $table);

}