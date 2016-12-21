<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/EventListener/ExceptionListenerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\EventListener;

use App\EventListener\ExceptionListener;
use App\Tests\Helpers\PHPUnitUtil;
use App\Utils\JSON;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent as Event;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class ExceptionListenerTest
 *
 * @package AppBundle\unit\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionListenerTest extends KernelTestCase
{
    /**
     * @dataProvider dataProviderTestThatOnKernelExceptionMethodCallsLogger
     *
     * @param string $environment
     */
    public function testThatOnKernelExceptionMethodCallsLogger(string $environment)
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface    $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|Event              $stubEvent
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubEvent = $this->createMock(Event::class);

        $exception = new \Exception('test exception');

        $stubEvent
            ->expects(static::once())
            ->method('getException')
            ->willReturn($exception);

        $stubLogger
            ->expects(static::once())
            ->method('error')
            ->with((string)$exception);

        $listener = new ExceptionListener($stubLogger, $environment);
        $listener->onKernelException($stubEvent);
    }

    /**
     * @dataProvider dataProviderTestThatOnKernelExceptionMethodCallsLogger
     *
     * @param string $environment
     */
    public function testThatOnKernelExceptionMethodSetResponse(string $environment)
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface    $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|Event              $stubEvent
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubEvent = $this->createMock(Event::class);

        $exception = new \Exception('test exception');

        $stubEvent
            ->expects(static::once())
            ->method('getException')
            ->willReturn($exception);

        $stubEvent
            ->expects(static::once())
            ->method('setResponse');

        $listener = new ExceptionListener($stubLogger, $environment);
        $listener->onKernelException($stubEvent);
    }

    /**
     * @dataProvider dataProviderTestResponseHasExpectedStatusCode
     *
     * @param   int         $expectedStatus
     * @param   \Exception  $exception
     */
    public function testResponseHasExpectedStatusCode(int $expectedStatus, \Exception $exception)
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface        $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|HttpKernelInterface    $stubHttpKernel
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                $stubRequest
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubHttpKernel = $this->createMock(HttpKernelInterface::class);
        $stubRequest = $this->createMock(Request::class);

        // Create event
        $event = new Event($stubHttpKernel, $stubRequest, HttpKernelInterface::MASTER_REQUEST, $exception);

        // Process event
        $listener = new ExceptionListener($stubLogger, 'dev');
        $listener->onKernelException($event);

        static::assertEquals($expectedStatus, $event->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider dataProviderTestThatResponseHasExpectedKeys
     *
     * @param   array   $expectedKeys
     * @param   string  $environment
     */
    public function testThatResponseHasExpectedKeys(array $expectedKeys, string $environment)
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface        $stubLogger
         * @var \PHPUnit_Framework_MockObject_MockObject|HttpKernelInterface    $stubHttpKernel
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                $stubRequest
         */
        $stubLogger = $this->createMock(LoggerInterface::class);
        $stubHttpKernel = $this->createMock(HttpKernelInterface::class);
        $stubRequest = $this->createMock(Request::class);

        // Create event
        $event = new Event($stubHttpKernel, $stubRequest, HttpKernelInterface::MASTER_REQUEST, new \Exception('error'));

        // Process event
        $listener = new ExceptionListener($stubLogger, $environment);
        $listener->onKernelException($event);

        $result = JSON::decode($event->getResponse()->getContent(), true);

        static::assertEquals($expectedKeys, array_keys($result));
    }

    /**
     * @dataProvider dataProviderTestThatGetExceptionMessageMethodReturnsExpected
     *
     * @param   \Exception  $exception
     * @param   string      $environment
     * @param   string      $expectedMessage
     */
    public function testThatGetExceptionMessageMethodReturnsExpected(
        \Exception $exception,
        string $environment,
        string $expectedMessage
    ) {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface $stubLogger
         */
        $stubLogger = $this->createMock(LoggerInterface::class);

        $listener = new ExceptionListener($stubLogger, $environment);

        static::assertEquals($expectedMessage, PHPUnitUtil::callMethod($listener, 'getExceptionMessage', [$exception]));
    }

    /**
     * @return array
     */
    public function dataProviderTestThatOnKernelExceptionMethodCallsLogger(): array
    {
        return [
            ['dev'],
            ['prod'],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatGetExceptionMessageMethodReturnsExpected(): array
    {
        return [
            [
                new \Exception(\Exception::class),
                'dev',
                \Exception::class
            ],
            [
                new AccessDeniedHttpException(AccessDeniedHttpException::class),
                'dev',
                AccessDeniedHttpException::class,
            ],
            [
                new DBALException(DBALException::class),
                'dev',
                DBALException::class,
            ],
            [
                new ORMException(ORMException::class),
                'dev',
                ORMException::class,
            ],
            [
                new \Exception(\Exception::class),
                'prod',
                \Exception::class
            ],
            [
                new AccessDeniedHttpException(AccessDeniedHttpException::class),
                'prod',
                'Access denied.',
            ],
            [
                new DBALException(DBALException::class),
                'prod',
                'Database error.',
            ],
            [
                new ORMException(ORMException::class),
                'prod',
                'Database error.',
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestResponseHasExpectedStatusCode(): array
    {
        return [
            [
                Response::HTTP_INTERNAL_SERVER_ERROR,
                new \Exception(\Exception::class)
            ],
            [
                Response::HTTP_INTERNAL_SERVER_ERROR,
                new \BadMethodCallException(\BadMethodCallException::class)
            ],
            [
                Response::HTTP_UNAUTHORIZED,
                new AuthenticationException(AuthenticationException::class)
            ],
            [
                Response::HTTP_BAD_REQUEST,
                new HttpException(Response::HTTP_BAD_REQUEST, HttpException::class),
            ],
            [
                Response::HTTP_I_AM_A_TEAPOT,
                new HttpException(Response::HTTP_I_AM_A_TEAPOT, HttpException::class),
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatResponseHasExpectedKeys(): array
    {
        return [
            [
                ['message', 'code', 'status', 'debug'],
                'dev',
            ],
            [
                ['message', 'code', 'status'],
                'prod',
            ]
        ];
    }
}
