<?php
/**
 * /tests/AppBundle/Controller/UserControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Controller;

use App\Tests\WebTestCase;
use App\Utils\JSON;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 *
 * @category    Tests
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserControllerTest extends WebTestCase
{
    /**
     * @dataProvider dataProviderTestThatOnlyAdminUsersCanListUsers
     *
     * @param   string  $username
     * @param   string  $password
     * @param   integer $expectedStatus
     */
    public function testThatOnlyAdminUsersCanListUsers($username, $password, $expectedStatus)
    {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('GET', '/user');

        // Check that HTTP status code is correct
        $this->assertEquals(
            $expectedStatus,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for /user request\n' . $client->getResponse()
        );

        // Get response data
        $responseData = JSON::decode($client->getResponse()->getContent());

        // Forbidden request - not admin user
        if ($expectedStatus === Response::HTTP_FORBIDDEN) {
            $attributes = ['message', 'status', 'code'];

            foreach ($attributes as $attribute) {
                $this->assertObjectHasAttribute(
                    $attribute,
                    $responseData,
                    'Response did not contain expected attribute'
                );
            }

            // Get response object keys
            $keys = array_keys(get_object_vars($responseData));

            $this->assertEquals(
                sort($attributes),
                sort($keys),
                'Response contains keys that are not expected'
            );

            $this->assertEquals('Access denied.', $responseData->message);
            $this->assertEquals('403', $responseData->status);
            $this->assertEquals('0', $responseData->code);
        } else { // Otherwise check that response has correct output
            $this->assertTrue(is_array($responseData), 'Response did not return array of users.');
            $this->assertEquals(5, count($responseData), 'Response did not contain expected number of users.');
        }
    }

    /**
     * Data provider method for 'testThatOnlyAdminUsersCanListUsers' test
     *
     * @return array
     */
    public function dataProviderTestThatOnlyAdminUsersCanListUsers()
    {
        return [
            ['john', 'doe', Response::HTTP_FORBIDDEN],
            ['john.doe@test.com', 'doe', Response::HTTP_FORBIDDEN],

            ['john-logged', 'doe-logged', Response::HTTP_FORBIDDEN],
            ['john.doe-logged@test.com', 'doe-logged', Response::HTTP_FORBIDDEN],

            ['john-user', 'doe-user', Response::HTTP_FORBIDDEN],
            ['john.doe-user@test.com', 'doe-user', Response::HTTP_FORBIDDEN],

            ['john-admin', 'doe-admin', Response::HTTP_OK],
            ['john.doe-admin@test.com', 'doe-admin', Response::HTTP_OK],

            ['john-root', 'doe-root', Response::HTTP_OK],
            ['john.doe-root@test.com', 'doe-root', Response::HTTP_OK],
        ];
    }
}
