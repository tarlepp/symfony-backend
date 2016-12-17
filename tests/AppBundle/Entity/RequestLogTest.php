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
}
