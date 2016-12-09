<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/ResponseLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Entity\RequestLog as RequestLogEntity;
use App\Repository\RequestLog as RequestLogRepository;
use App\Services\Interfaces\ResponseLogger as ResponseLoggerInterface;
use App\Services\ResponseLogger;
use App\Services\Rest\RequestLog as RequestLogService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
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
        $this->beConstructedWith($logger, $service, 'dev');
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

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|Request               $request
     * @param   \PhpSpec\Wrapper\Collaborator|Response              $response
     * @param   \PhpSpec\Wrapper\Collaborator|HeaderBag             $headerBag
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService     $service
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogRepository  $repository
     */
    function it_should_create_new_RequestLog_entity(
        Request $request,
        Response $response,
        HeaderBag $headerBag,
        RequestLogService $service,
        RequestLogRepository $repository
    ) {
        // Mock HeaderBag call
        $headerBag->all()->shouldBeCalled()->willReturn([]);

        // Attach mocked HeaderBag to Request
        $request->headers = $headerBag;

        // Mock all necessary in Request
        $request->getClientIp()->shouldBeCalled()->willReturn('fake ip');
        $request->getRealMethod()->shouldBeCalled()->willReturn('fake real method');
        $request->getScheme()->shouldBeCalled()->willReturn('fake scheme');
        $request->getHttpHost()->shouldBeCalled()->willReturn('fake http host');
        $request->getBasePath()->shouldBeCalled()->willReturn('fake base path');
        $request->getScriptName()->shouldBeCalled()->willReturn('fake script name');
        $request->getPathInfo()->shouldBeCalled()->willReturn('fake path info');
        $request->getRequestUri()->shouldBeCalled()->willReturn('fake request uri');
        $request->getUri()->shouldBeCalled()->willReturn('fake uri');
        $request->get('_controller', '')->shouldBeCalled()->willReturn('fake::controller');
        $request->getContentType()->shouldBeCalled()->willReturn('fake content type');
        $request->getMimeType(Argument::any())->shouldBeCalled()->willReturn('fake mime type');
        $request->getContent(Argument::any())->shouldBeCalled()->willReturn('fake content');
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(false);

        // Mock all necessary in Response
        $response->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response->getContent()->shouldBeCalled()->willReturn('fake content');

        // Mock repository method
        $repository->cleanHistory()->shouldBeCalled();

        // Request log should be save
        $service->getRepository()->shouldBeCalled()->willReturn($repository);
        $service->save(Argument::type(RequestLogEntity::class), Argument::type('bool'))->shouldBeCalled();

        // And finally make actual call
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setMasterRequest(true);
        $this->handle();
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|Request           $request
     * @param   \PhpSpec\Wrapper\Collaborator|Response          $response
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface   $logger
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService $service
     */
    function it_should_log_possible_error_in_any_other_environments_expect_dev(
        Request $request,
        Response $response,
        LoggerInterface $logger,
        RequestLogService $service
    ) {
        // Construct with 'prod' environment
        $this->beConstructedWith($logger, $service, 'prod');

        // Make first sub-method to throw an exception
        $request->getClientIp()->willThrow(\Exception::class);

        // Set required mock data
        $this->setRequest($request);
        $this->setResponse($response);

        // It should log this error
        $logger->error(Argument::any())->shouldBeCalled();

        $this->handle();
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|Request   $request
     * @param   \PhpSpec\Wrapper\Collaborator|Response  $response
     */
    function it_should_throw_an_exception_if_error_occurred_in_dev_environment(
        Request $request,
        Response $response
    ) {
        // Make first sub-method to throw an exception
        $request->getClientIp()->willThrow(\Exception::class);

        // Set required mock data
        $this->setRequest($request);
        $this->setResponse($response);

        // And it should throw an exception
        $this->shouldThrow(\Exception::class)->during('handle');
    }
}
