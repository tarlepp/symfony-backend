<?php
declare(strict_types=1);
/**
 * /src/App/Services/Interfaces/ResponseLogger.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

use App\Services\Rest\RequestLog as RequestLogService;
use Psr\Log\LoggerInterface;
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
     * ResponseLogger constructor.
     *
     * @param   LoggerInterface     $logger
     * @param   RequestLogService   $service
     * @param   string              $environment
     */
    public function __construct(LoggerInterface $logger, RequestLogService $service, string $environment);

    /**
     * Setter for response object.
     *
     * @param   Response $response
     *
     * @return  ResponseLogger
     */
    public function setResponse(Response $response): ResponseLogger;

    /**
     * Setter for request object.
     *
     * @param   Request $request
     *
     * @return  ResponseLogger
     */
    public function setRequest(Request $request): ResponseLogger;

    /**
     * Setter method for current user.
     *
     * @param   UserInterface|null $user
     *
     * @return  ResponseLogger
     */
    public function setUser(UserInterface $user = null): ResponseLogger;

    /**
     * Setter method for 'master request' info.
     *
     * @param bool $masterRequest
     *
     * @return ResponseLogger
     */
    public function setMasterRequest(bool $masterRequest): ResponseLogger;

    /**
     * Method to handle current response and log it to database.
     *
     * @throws  \Exception
     */
    public function handle();
}
