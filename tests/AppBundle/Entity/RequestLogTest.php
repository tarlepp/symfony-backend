<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\RequestLog;
use App\Tests\EntityTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestLogTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLogTest extends EntityTestCase
{
    /**
     * @var RequestLog
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\RequestLog';

    /**
     * @dataProvider dataProviderTestThatSensitiveDataIsCleaned
     *
     * @param array $headers
     * @param array $expected
     */
    public function testThatSensitiveDataIsCleanedFromHeaders(array $headers, array $expected)
    {
        $requestLog = new RequestLog();

        $requestLog->setHeaders($headers);

        static::assertEquals($expected, $requestLog->getHeaders());
    }

    /**
     * @dataProvider dataProviderTestThatSensitiveDataIsCleaned
     *
     * @param array $parameters
     * @param array $expected
     */
    public function testThatSensitiveDataIsCleanedFromParameters(array $parameters, array $expected)
    {
        $requestLog = new RequestLog();

        $requestLog->setParameters($parameters);

        static::assertEquals($expected, $requestLog->getParameters());
    }

    /**
     * @dataProvider dataProviderTestThatDetermineParametersWorksLikeExpected
     *
     * @param   string  $content
     * @param   array   $expected
     */
    public function testThatDetermineParametersWorksLikeExpected(string $content, array $expected)
    {
        $requestLog = new RequestLog();
        $request = Request::create('', 'GET', [], [], [], [], $content);

        static::assertEquals($expected, $this->invokeMethod($requestLog, 'determineParameters', [$request]));
    }

    /**
     * Call protected/private method of a class.
     *
     * @param   object  $object     Instantiated object that we will run method on.
     * @param   string  $methodName Method name to call
     * @param   array   $parameters Array of parameters to pass into method.
     *
     * @return  mixed Method return.
     */
    public function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatSensitiveDataIsCleaned(): array
    {
        return [
            [
                ['passWord' => 'password'],
                ['passWord' => '*** REPLACED ***'],
            ],
            [
                ['token' => 'secret token'],
                ['token' => '*** REPLACED ***'],
            ],
            [
                ['Authorization' => 'authorization bearer'],
                ['Authorization' => '*** REPLACED ***'],
            ],
            [
                ['cookie' => ['cookie']],
                ['cookie' => '*** REPLACED ***'],
            ],
            [
                ['someheader' => [
                    'foo'       => 'bar',
                    'password'  => 'some password',
                ]],
                ['someheader' => [
                    'foo'       => 'bar',
                    'password'  => '*** REPLACED ***',
                ]],
            ]
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatDetermineParametersWorksLikeExpected(): array
    {
        return [
            [
                '{"foo":"bar"}',
                ['foo' => 'bar'],
            ],
            [
                'foo=bar',
                ['foo' => 'bar'],
            ],
            [
                'false',
                [false],
            ]
        ];
    }
}
