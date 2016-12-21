<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/DefaultControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerTest
 *
 * @category    Tests
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Method to test '/' route with all supported HTTP methods.
     *
     * @dataProvider testIndexProvider
     *
     * @param   string  $method             HTTP method (GET, POST, etc.)
     * @param   integer $expectedStatusCode Expected HTTP status code
     * @param   string  $expectedContent    Expected content
     */
    public function testIndex(string $method, int $expectedStatusCode, string $expectedContent)
    {
        $client = static::createClient();
        $client->request($method, '/');

        // Check that HTTP status code is correct
        static::assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for method \'' . $method . '\''
        );

        // Check that actual response content is correct
        static::assertEquals(
            $expectedContent,
            $client->getResponse()->getContent(),
            'HTTP response was not expected for method \'' . $method . '\''
        );
    }

    /**
     * Data provider for testIndex method.
     *
     * @return array
     */
    public function testIndexProvider(): array
    {
        return [
            ['GET',     Response::HTTP_OK, ''],
            ['HEAD',    Response::HTTP_OK, ''],
            ['POST',    Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('POST')],
            ['PUT',     Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('PUT')],
            ['DELETE',  Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('DELETE')],
            ['TRACE',   Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('TRACE')],
            ['OPTIONS', Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('OPTIONS')],
            ['CONNECT', Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('CONNECT')],
            ['PATCH',   Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('PATCH')],
        ];
    }

    /**
     * Helper method to get default "No route..." error message for specified HTTP method.
     *
     * @param   string  $method HTTP method
     *
     * @return  string
     */
    private function getContent($method): string
    {
        return json_encode([
            'message'   => 'No route found for "' . $method . ' /": Method Not Allowed (Allow: GET, HEAD)',
            'code'      => 0,
            'status'    => 405,
        ]);
    }
}
