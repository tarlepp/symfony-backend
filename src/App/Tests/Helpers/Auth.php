<?php
declare(strict_types=1);
/**
 * /src/App/Tests/Helpers/Auth.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Helpers;

use App\Utils\JSON;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Auth
 *
 * @package App\Tests\Helpers
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Auth
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Credential cache
     *
     * @var \string[]
     */
    private $cache = [];

    /**
     * Auth constructor.
     *
     * @param   ContainerInterface  $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method to get authorization headers for specified user.
     *
     * @param   string|null $username
     * @param   string|null $password
     *
     * @return  array
     *
     * @throws  \Exception
     */
    public function getAuthorizationHeadersForUser(string $username = null, string $password = null)
    {
        $key = sha1($username . $password);

        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->makeLogin($username, $password);
        }

        // Return valid authorization headers for user
        return $this->getAuthorizationHeaders($this->cache[$key]);
    }

    /**
     * Method to get authorization headers for specified token.
     *
     * @param   string  $token
     *
     * @return  array
     */
    public function getAuthorizationHeaders(string $token)
    {
        return [
            'CONTENT_TYPE'          => 'application/json',
            'HTTP_AUTHORIZATION'    => 'Bearer ' . $token,
        ];
    }

    /**
     * Method to make actual login to application with specified username and password.
     *
     * @throws  \DomainException
     *
     * @param   string  $username
     * @param   string  $password
     *
     * @return  string
     */
    private function makeLogin(string $username, string $password)
    {
        // Get client
        $client = $this->container->get('test.client');

        // Create request to make login using given credentials
        $client->request('POST', '/auth/getToken', ['username' => $username, 'password' => $password]);

        // Verify that login was ok
        if ($client->getResponse()->getStatusCode() !== 200) {
            throw new \DomainException('User login failed...');
        }

        return JSON::decode($client->getResponse()->getContent())->token;
    }
}
