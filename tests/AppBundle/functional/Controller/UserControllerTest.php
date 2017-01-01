<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/UserControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Entity\User;
use App\Tests\Helpers\PHPUnitUtil;
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
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        PHPUnitUtil::resetDatabaseStatus();

        parent::tearDownAfterClass();
    }

    /**
     * @dataProvider dataProviderTestThatOnlyAdminUsersCanListUsers
     *
     * @param   string  $username
     * @param   string  $password
     * @param   integer $expectedStatus
     */
    public function testThatOnlyAdminUsersCanListUsers(string $username, string $password, int $expectedStatus)
    {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('GET', '/user');

        // Check that HTTP status code is correct
        static::assertEquals(
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
                static::assertObjectHasAttribute(
                    $attribute,
                    $responseData,
                    'Response did not contain expected attribute'
                );
            }

            // Get response object keys
            $keys = array_keys(get_object_vars($responseData));

            static::assertEquals(
                sort($attributes),
                sort($keys),
                'Response contains keys that are not expected'
            );

            static::assertEquals('Access denied.', $responseData->message);
            static::assertEquals('403', $responseData->status);
            static::assertEquals('0', $responseData->code);
        } else { // Otherwise check that response has correct output
            static::assertTrue(is_array($responseData), 'Response did not return array of users.');
            static::assertCount(5, $responseData, 'Response did not contain expected number of users.');
        }
    }

    /**
     * @dataProvider dataProviderTestThatOnlyAdminUsersCanListUsers
     *
     * @param   string  $username
     * @param   string  $password
     * @param   integer $expectedStatus
     * @param   string  $id
     */
    public function testThatOnlyAdminUsersCanGetSingleUserData(
        string $username,
        string $password,
        int $expectedStatus,
        string $id
    ) {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('GET', '/user/' . $id);

        // Check that HTTP status code is correct
        static::assertEquals(
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
                static::assertObjectHasAttribute(
                    $attribute,
                    $responseData,
                    'Response did not contain expected attribute'
                );
            }

            // Get response object keys
            $keys = array_keys(get_object_vars($responseData));

            static::assertEquals(
                sort($attributes),
                sort($keys),
                'Response contains keys that are not expected'
            );

            static::assertEquals('Access denied.', $responseData->message);
            static::assertEquals('403', $responseData->status);
            static::assertEquals('0', $responseData->code);
        } else { // Otherwise check that response has correct output
            $attributes = ['id', 'username', 'firstname', 'surname', 'email'];

            foreach ($attributes as $attribute) {
                static::assertObjectHasAttribute(
                    $attribute,
                    $responseData,
                    'Response did not contain expected attribute'
                );
            }

            // Get response object keys
            $keys = array_keys(get_object_vars($responseData));

            static::assertEquals(
                sort($attributes),
                sort($keys),
                'Response contains keys that are not expected'
            );
        }
    }



    /**
     * @dataProvider dataProviderTestThatUserCannotDeleteHimSelf
     *
     * @param string $username
     * @param string $password
     * @param string $id
     */
    public function testThatRootUserCannotDeleteHimSelf(string $username, string $password, string $id)
    {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('DELETE', '/user/' . $id);

        // Check that HTTP status code is correct
        static::assertEquals(
            400,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for DELETE /user/{guid} request\n' . $client->getResponse()
        );

        // Get response data
        $responseData = JSON::decode($client->getResponse()->getContent());

        $attributes = ['message', 'status', 'code'];

        foreach ($attributes as $attribute) {
            static::assertObjectHasAttribute(
                $attribute,
                $responseData,
                'Response did not contain expected attribute'
            );
        }

        // Get response object keys
        $keys = array_keys(get_object_vars($responseData));

        static::assertEquals(
            sort($attributes),
            sort($keys),
            'Response contains keys that are not expected'
        );

        static::assertEquals('You can\'t remove yourself...', $responseData->message);
        static::assertEquals('400', $responseData->status);
        static::assertEquals('0', $responseData->code);
    }

    /**
     * @dataProvider dataProviderTestThatNonRootUsersCanAccessDeleteAction
     *
     * @param   string  $username
     * @param   string  $password
     * @param   string  $id
     */
    public function testThatNonRootUsersCanAccessDeleteAction(string $username, string $password, string $id)
    {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('DELETE', '/user/' . $id);

        $this->getContainer()->get('doctrine')->resetManager();

        // Check that HTTP status code is correct
        static::assertEquals(
            403,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for DELETE /user/{guid} request\n' . $client->getResponse()
        );
    }

    /**
     * @dataProvider dataProviderTestThatRootUserCanDeleteAnotherUser
     *
     * @param   string  $username
     * @param   string  $password
     * @param   User    $user
     * @param   int     $expectedStatusCode
     */
    public function testThatRootUserCanDeleteAnotherUser(
        string $username,
        string $password,
        User $user,
        int $expectedStatusCode
    ) {
        // Create request
        $client = $this->getClient($username, $password);
        $client->request('DELETE', '/user/' . $user->getId());

        // Check that HTTP status code is correct
        static::assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for DELETE /user/{guid} request\n' . $client->getResponse()
        );
    }

    /**
     * Data provider method for 'testThatOnlyAdminUsersCanListUsers' test
     *
     * @return array
     */
    public function dataProviderTestThatOnlyAdminUsersCanListUsers(): array
    {
        // Fetch users and pick random of those
        $users = $this->getContainer()->get('app.services.rest.user')->find();
        $key = array_rand($users, 1);

        /** @var User $user */
        $user = $users[$key];

        return [
            ['john', 'doe', Response::HTTP_FORBIDDEN, $user->getId()],
            ['john.doe@test.com', 'doe', Response::HTTP_FORBIDDEN, $user->getId()],

            ['john-logged', 'doe-logged', Response::HTTP_FORBIDDEN, $user->getId()],
            ['john.doe-logged@test.com', 'doe-logged', Response::HTTP_FORBIDDEN, $user->getId()],

            ['john-user', 'doe-user', Response::HTTP_FORBIDDEN, $user->getId()],
            ['john.doe-user@test.com', 'doe-user', Response::HTTP_FORBIDDEN, $user->getId()],

            ['john-admin', 'doe-admin', Response::HTTP_OK, $user->getId()],
            ['john.doe-admin@test.com', 'doe-admin', Response::HTTP_OK, $user->getId()],

            ['john-root', 'doe-root', Response::HTTP_OK, $user->getId()],
            ['john.doe-root@test.com', 'doe-root', Response::HTTP_OK, $user->getId()],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatNonRootUsersCanAccessDeleteAction(): array
    {
        // Fetch users and pick random of those
        $users = $this->getContainer()->get('app.services.rest.user')->find();
        $key = array_rand($users, 1);

        /** @var User $user */
        $user = $users[$key];

        return [
            ['john', 'doe', $user->getId()],
            ['john.doe@test.com', 'doe', $user->getId()],

            ['john-logged', 'doe-logged', $user->getId()],
            ['john.doe-logged@test.com', 'doe-logged', $user->getId()],

            ['john-user', 'doe-user', $user->getId()],
            ['john.doe-user@test.com', 'doe-user', $user->getId()],

            ['john-admin', 'doe-admin', $user->getId()],
            ['john.doe-admin@test.com', 'doe-admin', $user->getId()],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatUserCannotDeleteHimSelf(): array
    {
        $user = $this->getContainer()->get('app.services.rest.user')->findOneBy(['username' => 'john-root']);

        return [
            ['john-root', 'doe-root', $user->getId()],
            ['john.doe-root@test.com', 'doe-root', $user->getId()],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatRootUserCanDeleteAnotherUser(): array
    {
        $users = $this->getContainer()
            ->get('app.services.rest.user')
            ->find(['and' => [['entity.username', 'neq', 'john-root']]]);

        return call_user_func_array(
            'array_merge',
            array_map(function (User $user) {
                return [
                    ['john-root', 'doe-root', $user, Response::HTTP_OK],
                    ['john.doe-root@test.com', 'doe-root', $user, Response::HTTP_NOT_FOUND],
                ];
            }, $users)
        );
    }
}
