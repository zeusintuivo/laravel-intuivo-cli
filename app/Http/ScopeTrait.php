<?php namespace PHIWeb\Http\Traits;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use PHIWeb\Http\Requests;
use Illuminate\Support\Facades\Input;
use ArrayAccess;

/**
 * Trait created for PHIWeb
 *
 * @author Jesus Alcaraz <jesus@gammapartners.com>, miguel <miguel@gammapartners.com>
 * @version 1
 */
trait ScopeTrait {

    /**
     * Get the fields from the query string
     * @return array|null
     */
    protected function getFieldsArray()
    {
        $fieldsString = Input:: get( 'fields' );
        $columns = is_null( $fieldsString ) ? null : explode( ",", $fieldsString );

        return $columns;
    }

    /**
     * Get the where conditions from the query string
     * @return array
     */
    protected function getConditionsArray()
    {
        $conditionsString = Input:: get( 'filter' );
        $conditions = is_null( $conditionsString ) ? [ ] : explode( ",", $conditionsString );

        return $conditions;
    }

    /**
     * Get the Items per page from the query string
     * @return int|null
     */
    protected function getItemsPerPage()
    {
        return Input:: get( 'perPage', 10 );
    }

    /**
     * Get the sort by field from the query string
     * @return mixed
     */
    protected function getSortBy()
    {
        return Input:: get( 'sort', 'created_at' );
    }

    /**
     * Get the sort direction from the query string
     * Default asc
     * @return string
     */
    protected function getSortDirection()
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
    protected function getWithArray()
    {
        return is_null( Input:: get( 'with' ) ) ? [ ] : explode( ",", Input:: get( 'with' ) );
    }

    protected function getWithPaginate()
    {
        return Input::get( 'withPag', 'true' ) == 'true';
    }



}