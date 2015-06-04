<?php namespace PHIWeb\Http\Controllers\Traits;

use PHIWeb\Http\Requests;
use Illuminate\Support\Facades\Input;

/**
 * Trait Controller created for PHIWeb
 *
 * use PHIWeb\Http\Controllers\Traits\ControllableArrays;
 *     use ControllableArrays;
 *
 * @author Jesus Alcaraz <jesus@gammapartners.com>
 * @version 7
 */
trait ControllableArrays  
{

    /**
     * Get the fields from the query string
     * @return array|null
     */
    public function getFieldsArray()
    {
        $fieldsString = Input:: get( 'fields' );
        $columns = is_null( $fieldsString ) ? null : explode( ",", $fieldsString );

        return $columns;
    }

    /**
     * Get the where conditions from the query string
     * @return array
     */
    public function getConditionsArray()
    {
        $conditionsString = Input:: get( 'filter' );
        $conditions = is_null( $conditionsString ) ? [ ] : explode( ",", $conditionsString );

        return $conditions;
    }

    /**
     * Get the Items per page from the query string
     * @return int|null
     */
    public function getItemsPerPage()
    {
        return Input:: get( 'perPage', 10 );
    }

    /**
     * Get the sort by field from the query string
     * @return mixed
     */
    public function getSortBy()
    {
        return Input:: get( 'sort', 'created_at' );
    }

    /**
     * Get the sort direction from the query string
     * Default asc
     * @return string
     */
    public function getSortDirection()
    {
        return Input:: get( 'dir', 'asc' ) == 'asc' ? 'asc' : 'desc';
    }

    /**
     * Get the objects from the query string
     * The final response will the instance(s) that belong to the current instance
     * @return array|null
     * @TODO:
     * $string not defined
     */
    public function getWithArray()
    {
        return is_null( Input:: get( 'with' ) ) ? [ ] : explode( ",", Input:: get( 'with' ) );
    }

    public function getWithPaginate()
    {
        return Input::get( 'withPag', 'true' ) == 'true';
    }


}
