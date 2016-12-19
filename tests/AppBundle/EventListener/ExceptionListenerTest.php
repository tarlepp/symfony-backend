<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/EventListener/ExceptionListenerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\EventListener;

use App\EventListener\ExceptionListener;
use App\Tests\KernelTestCase;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ExceptionListenerTest
 *
 * @package AppBundle\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionListenerTest extends KernelTestCase
{
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

        static::assertEquals($expectedMessage, $this->invokeMethod($listener, 'getExceptionMessage', [$exception]));
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
}
