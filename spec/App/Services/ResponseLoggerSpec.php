<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/ResponseLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Entity\RequestLog as RequestLogEntity;
use App\Services\Rest\RequestLog as RequestLogService;
use App\Services\Interfaces\ResponseLogger as ResponseLoggerInterface;
use App\Services\ResponseLogger;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseLoggerSpec
 *
 * @package spec\App\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseLoggerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface   $logger
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService $service
     */
    function let(
        LoggerInterface $logger,
        RequestLogService $service
    ) {
        $this->beConstructedWith($logger, $service, 'spec');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseLogger::class);
        $this->shouldImplement(ResponseLoggerInterface::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|Response          $response
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService $service
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogEntity  $requestLog
     */
    function it_should_do_nothing_if_request_is_not_set(
        Response $response,
        RequestLogService $service,
        RequestLogEntity $requestLog
    ) {
        $this->setResponse($response);

        $service->save($requestLog)->shouldNotBeCalled();

        $this->handle();
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|Request           $request
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService $service
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogEntity  $requestLog
     */
    function it_should_do_nothing_if_response_is_not_set(
        Request $request,
        RequestLogService $service,
        RequestLogEntity $requestLog
    ) {
        $this->setRequest($request);

        $service->save($requestLog)->shouldNotBeCalled();

        $this->handle();
    }
}
