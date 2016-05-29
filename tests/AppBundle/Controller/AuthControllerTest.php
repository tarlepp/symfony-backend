<?php
/**
 * /tests/AppBundle/Controller/AuthControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Controller;

use App\Fixtures\UserFixtureLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthControllerTest
 *
 * @category    Tests
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthControllerTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        // We need to boot kernel up to get Entity Manager
        self::bootKernel();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();

        // Create new loader and add necessary fixtures to be loaded
        $loader = new Loader();
        $loader->addFixture(new UserFixtureLoader());

        // And load fixtures to the database
        $executor = new ORMExecutor($em, new ORMPurger());
        $executor->execute($loader->getFixtures());

        var_dump('loaded fixtures');

        $users = $em->getRepository('AppBundle:User')->findAll();

        var_dump('data from database:');
        var_dump(array_map(function($user) {
            $foo = [
                $user->getId(),
                $user->getUsername(),
                $user->getPassword(),
            ];

            return implode(' - ', $foo);
        }, $users));
    }

    /**
     *
     * @dataProvider providerTestThatValidCredentialsWork
     *
     * @param $username
     * @param $password
     */
    public function testThatValidCredentialsWork($username, $password)
    {
        $client = static::createClient();
        $client->request('POST', '/auth/getToken', ['username' => $username, 'password' => $password]);

        var_dump('test input:');
        var_dump(
            ['username' => $username, 'password' => $password]
        );

        $this->assertResponseSuccess($client->getResponse());

        /*
        // Check that HTTP status code is correct
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            'User login was not successfully'
        );
        */
    }

    /**
     *
     * @dataProvider providerTestThatInvalidCredentialsWontWork
     *
     * @param $username
     * @param $password
     */
    public function testThatInvalidCredentialsWontWork($username, $password)
    {
        $client = static::createClient();
        $client->request('POST', '/auth/getToken', ['username' => $username, 'password' => $password]);

        // Check that HTTP status code is correct
        $this->assertEquals(
            401,
            $client->getResponse()->getStatusCode(),
            'Hmm, weird invalid user can log in the system - take this serious, very serious...'
        );
    }

    /**
     * @dataProvider providerTestThatNotSupportedMethodsReturn405
     *
     * @param $method
     * @param $expectedStatusCode
     * @param $ExpectedContent
     */
    public function testThatNotSupportedMethodsReturn405($method, $expectedStatusCode, $ExpectedContent)
    {
        $client = static::createClient();
        $client->request($method, '/auth/getToken');

        // Check that HTTP status code is correct
        $this->assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for method \'' . $method . '\''
        );

        // Check that actual response content is correct
        $this->assertEquals(
            $ExpectedContent,
            $client->getResponse()->getContent(),
            'HTTP response was not expected for method \'' . $method . '\''
        );
    }

    /**
     * Data provider method for 'testThatValidCredentialsWork' test
     *
     * @return array
     */
    public function providerTestThatValidCredentialsWork()
    {
        return [
            ['john', 'doe'],
            ['john.doe@test.com', 'doe'],
        ];
    }

    /**
     * Data provider method for 'testThatInvalidCredentialsWontWork' test
     *
     * @return array
     */
    public function providerTestThatInvalidCredentialsWontWork()
    {
        return [
            [null, null],
            ['a', 'a'],
            ['', ''],
            ['john', 'doẽ'],
            ['john.doe@test.com', ''],
        ];
    }

    /**
     * Data provider method for 'testThatNotSupportedMethodsReturn405' test
     *
     * @return array
     */
    public function providerTestThatNotSupportedMethodsReturn405()
    {
        return [
            ['GET',     Response::HTTP_METHOD_NOT_ALLOWED, $this->getContent('GET')],
            ['HEAD',    Response::HTTP_METHOD_NOT_ALLOWED, ''],
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
    private function getContent($method)
    {
        return json_encode([
            'message'   => 'No route found for "' . $method . ' /auth/getToken": Method Not Allowed (Allow: POST)',
            'code'      => 0,
            'status'    => 405,
        ]);
    }

    protected function assertResponseSuccess(Response $response)
    {
        libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML($response->getContent());
        $xpath = new \DOMXpath($dom);
        $result = $xpath->query('//div[contains(@class,"text-exception")]/h1');
        $exception = null;

        if ($result->length) {
            $exception = $result->item(0)->nodeValue;
        } else {
            $exception = $response->getContent();
        }

        $this->assertEquals(200, $response->getStatusCode(), $exception ? 'Exception: "'.$exception.'"' : null);
    }
}