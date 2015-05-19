<?php namespace PHIWeb\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use ArrayAccess;

/**
 * Trait created for PHIWeb
 *
 * @author Jesus Alcaraz <jesus@gammapartners.com>, miguel <miguel@gammapartners.com>
 * @version 1
 */
trait ModelQueries {

 
    /**
     * Scope to filter the results building a query
     *
     * @param $queryBuilder
     * @param array $conditions
     *
     * @return mixed
     * @author miguel <miguel@gammapartners.com>
     */
    protected function filterConditions($queryBuilder, $conditions = [ ], $column, $dir)
    {
        $queryBuilder = self::getFilterQuery( $queryBuilder, $conditions, $column, $dir , $queryBuilder->getTable());

        return $queryBuilder;
    }


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
    public static function getFilterQuery($query, $conditions = [ ], $orderByColumn = 'created_at', $dir = 'asc', $table)
    {

        $clauses = array();

        foreach ( $conditions as $index => $value ) {
            $match = strrpos( $value, "*=" ) ? 'APROX' : 'EXACT';

            switch ( $match ) {
                case 'APROX':
                    $a = explode( "*=", $value );

                    $clauses[ $index ]['column'] = $table . "." . $a[0];
                    $clauses[ $index ]['operator'] = 'like';
                    $clauses[ $index ]['value'] = '%' . $a[1] . '%';

                    break;

                case 'EXACT':

                    $a = explode( "=", $value );

                    $clauses[ $index ]['column'] = $table . "." . $a[0];
                    $clauses[ $index ]['operator'] = '=';
                    $clauses[ $index ]['value'] = $a[1];

                    break;

                default:

            }

        }

        return $query->where( function ($query) use ($clauses) {
            foreach ( $clauses as $index => $clause ) {
                $query->orWhere( $clause['column'], $clause['operator'], $clause['value'] );
            }
        } )
            ->orderBy( $orderByColumn, $dir );
    }

}