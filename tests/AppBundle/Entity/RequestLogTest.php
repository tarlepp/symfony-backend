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
     * @dataProvider dataProviderTestThatSensitiveDataIsCleanedFromHeaders
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
     * @return array
     */
    public function dataProviderTestThatSensitiveDataIsCleanedFromHeaders(): array
    {
        return [
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
