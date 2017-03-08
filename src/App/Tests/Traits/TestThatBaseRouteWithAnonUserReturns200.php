<?php
declare(strict_types = 1);
/**
 * /src/App/Tests/Traits/TestThatBaseRouteReturns200.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Traits;

use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class TestThatBaseRouteWithAnonUserReturns200
 *
 * @package App\Tests\Traits
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  static  Client  createClient()
 * @method  static  void    assertSame($expected, $actual, $message = '')
 */
trait TestThatBaseRouteWithAnonUserReturns200
{
    /**
     * Simple test trait to check that controller base route returns 200 for anon users.
     */
    public function testThatBaseRouteReturns200()
    {
        $client = static::createClient();
        $client->request('GET', self::$baseRoute);

        // Check that HTTP status code is correct
        static::assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for GET ' . self::$baseRoute . "\n" . $client->getResponse()
        );
    }
}
