<?php
declare(strict_types=1);
/**
 * /tests/AppBundle/integration/Services/ResponseLoggerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Services;

use App\Entity\User as UserEntity;
use App\Repository\RequestLog as RequestLogRepository;
use App\Services\ResponseLogger;
use App\Services\Rest\RequestLog as RequestLogService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseLoggerTest
 *
 * @package AppBundle\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseLoggerTest extends KernelTestCase
{
    public function testThatHandleMethodDoesNothingIfRequestIsNotPresent()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface    $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogService  $stubRequestLogService
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubRequestLogService = $this->createMock(RequestLogService::class);

        // Create ResponseLogger and call handle method
        $responseLogger = new ResponseLogger($stubLogger, $stubRequestLogService, '');
        $responseLogger->handle();
    }

    public function testThatServiceMethodsAreCalled()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface        $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogService      $stubRequestLogService
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|Response               $stubResponse
         * @var \PHPUnit_Framework_MockObject_MockObject|UserEntity             $stubUser
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogRepository   $stubRepository
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubRequestLogService = $this->createMock(RequestLogService::class);
        $stubRequest = $this->createMock(Request::class);
        $stubResponse = $this->createMock(Response::class);
        $stubUser = $this->createMock(UserEntity::class);
        $stubRepository = $this->createMock(RequestLogRepository::class);

        $stubRepository
            ->expects(static::once())
            ->method('cleanHistory');

        $stubRequestLogService
            ->expects(static::once())
            ->method('getRepository')
            ->willReturn($stubRepository);

        $stubRequestLogService
            ->expects(static::once())
            ->method('save')
            ->withAnyParameters();

        $this->mockRequestMethods($stubRequest);
        $this->mockResponseMethods($stubResponse);

        // Create ResponseLogger and call handle method
        $responseLogger = new ResponseLogger($stubLogger, $stubRequestLogService, '');
        $responseLogger->setRequest($stubRequest);
        $responseLogger->setResponse($stubResponse);
        $responseLogger->setUser($stubUser);
        $responseLogger->setMasterRequest(true);
        $responseLogger->handle();
    }

    public function testThatLoggerIsCalledSilentlyWhenExceptionIsThrown()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface        $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogService      $stubRequestLogService
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|Response               $stubResponse
         * @var \PHPUnit_Framework_MockObject_MockObject|UserEntity             $stubUser
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogRepository   $stubRepository
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubRequestLogService = $this->createMock(RequestLogService::class);
        $stubRequest = $this->createMock(Request::class);
        $stubResponse = $this->createMock(Response::class);
        $stubUser = $this->createMock(UserEntity::class);

        $stubRequestLogService
            ->expects(static::once())
            ->method('save')
            ->willThrowException(new \Exception('some error...'));

        $stubLogger
            ->expects(static::once())
            ->method('error');

        $this->mockRequestMethods($stubRequest);
        $this->mockResponseMethods($stubResponse);

        // Create ResponseLogger and call handle method
        $responseLogger = new ResponseLogger($stubLogger, $stubRequestLogService, '');
        $responseLogger->setRequest($stubRequest);
        $responseLogger->setResponse($stubResponse);
        $responseLogger->setUser($stubUser);
        $responseLogger->setMasterRequest(true);
        $responseLogger->handle();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage some error...
     */
    public function testThatLoggerThrowsAnExceptionInDevMode()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface        $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogService      $stubRequestLogService
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|Response               $stubResponse
         * @var \PHPUnit_Framework_MockObject_MockObject|UserEntity             $stubUser
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestLogRepository   $stubRepository
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubRequestLogService = $this->createMock(RequestLogService::class);
        $stubRequest = $this->createMock(Request::class);
        $stubResponse = $this->createMock(Response::class);
        $stubUser = $this->createMock(UserEntity::class);

        $stubRequestLogService
            ->expects(static::once())
            ->method('save')
            ->willThrowException(new \Exception('some error...'));

        $this->mockRequestMethods($stubRequest);
        $this->mockResponseMethods($stubResponse);

        // Create ResponseLogger and call handle method
        $responseLogger = new ResponseLogger($stubLogger, $stubRequestLogService, 'dev');
        $responseLogger->setRequest($stubRequest);
        $responseLogger->setResponse($stubResponse);
        $responseLogger->setUser($stubUser);
        $responseLogger->setMasterRequest(true);
        $responseLogger->handle();
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|Request $stubRequest
     */
    private function mockRequestMethods(\PHPUnit_Framework_MockObject_MockObject $stubRequest)
    {
        $stubHeaderBag = $this->createMock(HeaderBag::class);

        $stubHeaderBag
            ->expects(static::once())
            ->method('all')
            ->willReturn([]);

        $stubRequest->headers = $stubHeaderBag;

        $stubRequest
            ->expects(static::once())
            ->method('getClientIp')
            ->willReturn('fake ip');

        $stubRequest
            ->expects(static::once())
            ->method('getClientIp')
            ->willReturn('fake ip');

        $stubRequest
            ->expects(static::once())
            ->method('getRealMethod')
            ->willReturn('fake real method');

        $stubRequest
            ->expects(static::once())
            ->method('getScheme')
            ->willReturn('fake scheme');

        $stubRequest
            ->expects(static::once())
            ->method('getHttpHost')
            ->willReturn('fake http host');

        $stubRequest
            ->expects(static::once())
            ->method('getBasePath')
            ->willReturn('fake base path');

        $stubRequest
            ->expects(static::once())
            ->method('getScriptName')
            ->willReturn('fake script name');

        $stubRequest
            ->expects(static::once())
            ->method('getPathInfo')
            ->willReturn('fake path info');

        $stubRequest
            ->expects(static::once())
            ->method('getRequestUri')
            ->willReturn('fake request uri');

        $stubRequest
            ->expects(static::once())
            ->method('getUri')
            ->willReturn('fake uri');

        $stubRequest
            ->expects(static::exactly(2))
            ->method('get')
            ->willReturn('fake::controller');

        $stubRequest
            ->expects(static::exactly(2))
            ->method('getContentType')
            ->willReturn('fake content type');

        $stubRequest
            ->expects(static::once())
            ->method('getMimeType')
            ->willReturn('fake mime type');

        $stubRequest
            ->expects(static::exactly(4))
            ->method('getContent')
            ->willReturn('fake content');

        $stubRequest
            ->expects(static::once())
            ->method('isXmlHttpRequest')
            ->willReturn(false);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|Response $stubResponse
     */
    private function mockResponseMethods(\PHPUnit_Framework_MockObject_MockObject $stubResponse)
    {
        $stubResponse
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $stubResponse
            ->expects(static::once())
            ->method('getContent')
            ->willReturn('');
    }
}
