<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/PlaygroundControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Tests\Traits\TestThatBaseRouteWithAnonUserReturns200;
use App\Tests\Traits\TestThatBaseRouteWithAnonUserReturns401;
use App\Tests\WebTestCase;

/**
 * Class PlaygroundControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class PlaygroundControllerTest extends WebTestCase
{
    static protected $baseRoute = '/playground';

    use TestThatBaseRouteWithAnonUserReturns200;

    /**
     * @dataProvider dataProviderTestThatResponseIsExpectedWithLoggedInUser
     *
     * @param string $username
     * @param string $password
     */
    public function testThatResponseIsExpectedWithLoggedInUser(string $username, string $password)
    {
        $client = $this->getClient($username, $password);
        $client->request('GET', self::$baseRoute);

        // Check that HTTP status code is correct
        static::assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for GET ' . self::$baseRoute . "\n" . $client->getResponse()
        );

        static::assertSame(
            'Hello world',
            $client->getResponse()->getContent(),
            'HTTP response was not expected.'
        );
    }

    /**
     * @return array
     */
    public function dataProviderTestThatResponseIsExpectedWithLoggedInUser(): array
    {
        return [
            ['john', 'doe'],
            ['john.doe@test.com', 'doe'],

            ['john-logged', 'doe-logged'],
            ['john.doe-logged@test.com', 'doe-logged'],

            ['john-user', 'doe-user'],
            ['john.doe-user@test.com', 'doe-user'],

            ['john-admin', 'doe-admin'],
            ['john.doe-admin@test.com', 'doe-admin'],
        ];
    }
}
