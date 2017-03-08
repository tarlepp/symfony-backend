<?php
declare(strict_types = 1);
/**
 * /src/App/Tests/Traits/TestThatBaseRouteReturns401.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Traits;

use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class TestThatBaseRouteWithAnonUserReturns401
 *
 * @package App\Tests\Traits
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  static  Client  createClient()
 * @method  static  void    assertSame($expected, $actual, $message = '')
 */
trait TestThatBaseRouteWithAnonUserReturns401
{
    /**
     * Simple test trait to check that controller base route returns 401 for anon users.
     */
    public function testThatBaseRouteReturns401()
    {
        $client = static::createClient();
        $client->request('GET', self::$baseRoute);

        // Check that HTTP status code is correct
        static::assertSame(
            401,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for GET ' . self::$baseRoute . "\n" . $client->getResponse()
        );
    }
}
