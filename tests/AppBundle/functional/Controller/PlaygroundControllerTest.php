<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/PlaygroundControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Tests\WebTestCase;
use App\Utils\JSON;

/**
 * Class PlaygroundControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class PlaygroundControllerTest extends WebTestCase
{
    public function testThatTestActionReturnsExpected()
    {
        $client = $this->getClient('john', 'doe');
        $client->request(
            'GET',
            '/playground'
        );

        // Check that HTTP status code is correct
        static::assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            "HTTP status code was not expected for GET /playground\n" . $client->getResponse()
        );

        static::assertSame('Hello world', $client->getResponse()->getContent());
    }
}
