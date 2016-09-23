<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Helper/Request.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest\Helper;

use App\Utils\JSON;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Request
 *
 * @package App\Services\Rest\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Request
{
    /**
     * Method to get used criteria array for 'find' method.
     *
     * @throws  HttpException
     *
     * @param   HttpFoundationRequest   $request
     *
     * @return  array
     */
    public static function getCriteria(HttpFoundationRequest $request)
    {
        try {
            $userInput = array_filter(JSON::decode($request->get('where', '{}'), true));
        } catch (\LogicException $error) {
            throw new HttpException(
                HttpFoundationResponse::HTTP_BAD_REQUEST,
                'Current \'where\' parameter is not valid JSON.'
            );
        }

        return $userInput;
    }

    /**
     * Getter method for used order by option within 'find' method. Some examples below.
     *
     * Basic usage:
     *  ?order=column1                              => ORDER BY column1 ASC
     *  ?order=-column1                             => ORDER BY column2 DESC
     *
     * Array parameter usage:
     *  ?order[column1]=ASC                         => ORDER BY column1 ASC
     *  ?order[column1]=DESC                        => ORDER BY column1 DESC
     *  ?order[column1]=foobar                      => ORDER BY column1 ASC
     *  ?order[column1]=DESC&orderBy[column2]=DESC  => ORDER BY column1 DESC, column2 DESC
     *
     * @param   HttpFoundationRequest   $request
     *
     * @return  null|array
     */
    public static function getOrderBy(HttpFoundationRequest $request)
    {
        // Normalize parameter value
        $input = array_filter((array)$request->get('order', []));

        // Initialize output
        $output = [];

        /**
         * Lambda function to process user input for 'order' parameter and convert it to proper array that
         * Doctrine repository find method can use.
         *
         * @param   string          $value
         * @param   integer|string  $key
         */
        $iterator = function (&$value, $key) use (&$output) {
            $order = 'ASC';

            if (is_string($key)) {
                $column = $key;
                $order = in_array(strtoupper($value), ['ASC', 'DESC']) ? strtoupper($value) : $order;
            } else {
                $column = $value;
            }

            if ($column[0] === '-') {
                $column = substr($column, 1);
                $order = 'DESC';
            }

            $output[$column] = $order;
        };

        // Process user input
        array_walk($input, $iterator);

        return count($output) > 0 ? $output : null;
    }

    /**
     * Getter method for used limit option within 'find' method.
     *
     * @param   HttpFoundationRequest   $request
     *
     * @return  null|integer
     */
    public static function getLimit(HttpFoundationRequest $request)
    {
        return $request->get('limit', null);
    }

    /**
     * Getter method for used offset option within 'find' method.
     *
     * @param   HttpFoundationRequest   $request
     *
     * @return  null|integer
     */
    public static function getOffset(HttpFoundationRequest $request)
    {
        return $request->get('offset', null);
    }

    /**
     * Getter method for used search terms within 'find' method.
     *
     * @throws  HttpException
     *
     * @param   HttpFoundationRequest   $request
     *
     * @return  null|[]
     */
    public static function getSearchTerms(HttpFoundationRequest $request)
    {
        $search = $request->get('search', null);

        if (is_null($search)) {
            return null;
        }

        try {
            $input = JSON::decode($search, true);

            if (!array_key_exists('and', $input) && !array_key_exists('or', $input)) {
                throw new HttpException(
                    HttpFoundationResponse::HTTP_BAD_REQUEST,
                    'Given search parameter is not valid, within JSON provide \'and\' and/or \'or\' property.'
                );
            }

            /**
             * Lambda function to normalize JSON search terms.
             *
             * @param   string|array $terms
             */
            $iterator = function (&$terms) {
                if (!is_array($terms)) {
                    $terms = explode(' ', (string)$terms);
                }

                $terms = array_unique(array_filter($terms));
            };

            // Normalize user input, note that this support array and string formats on value
            array_walk($input, $iterator);

            $search = $input;
        } catch (\LogicException $error) {
            // By default we want to use 'OR' operand with given search words.
            $search = [
                'or' => array_unique(array_filter(explode(' ', $search)))
            ];
        }

        return $search;
    }
}
