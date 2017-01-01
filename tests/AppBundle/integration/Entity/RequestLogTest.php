<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\RequestLog;
use App\Tests\EntityTestCase;
use App\Tests\Helpers\PHPUnitUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestLogTest
 *
 * @package AppBundle\integration\Entity
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
    protected $entityName = RequestLog::class;

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

        static::assertEquals($expected, PHPUnitUtil::callMethod($requestLog, 'determineParameters', [$request]));
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
                ['someHeader' => [
                    'foo'       => 'bar',
                    'password'  => 'some password',
                ]],
                ['someHeader' => [
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
