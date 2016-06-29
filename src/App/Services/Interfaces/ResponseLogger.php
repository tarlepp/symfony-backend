<?php
/**
 * /src/App/Services/Interfaces/ResponseLogger.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Setter method for current user.
     *
     * @param   UserInterface|null $user
     *
     * @return  ResponseLogger
     */
    public function setUser(UserInterface $user = null);

    /**
     * Method to handle current response and log it to database.
     *
     * @return  void
     */
    public function handle();
}
