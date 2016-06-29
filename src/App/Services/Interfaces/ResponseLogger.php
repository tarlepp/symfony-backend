<?php
/**
 * /src/App/Services/Interfaces/ResponseLogger.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResponseLogger
 *
 * @package App\Services\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface ResponseLogger
{
    /**
     * Setter for response object.
     *
     * @param   Response $response
     *
     * @return  ResponseLogger
     */
    public function setResponse(Response $response);

    /**
     * Setter for request object.
     *
     * @param   Request     $request
     *
     * @return  ResponseLogger
     */
    public function setRequest(Request $request);

    /**
     * Method to handle current response and log it to database.
     *
     * @return  void
     */
    public function handle();
}
