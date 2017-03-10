<?php
declare(strict_types=1);
/**
 * /src/App/Tests/WebTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Tests\Helpers\Auth;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class WebTestCase extends KernelTestCase
{
    /**
     * @var Auth
     */
    private $authService;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected static function createClient(array $options = null, array $server = null)
    {
        $options = $options ?? [];
        $server = $server ?? [];

        static::bootKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    /**
     * Getter method for container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if (!($this->container instanceof ContainerInterface)) {
            self::bootKernel();

            $this->container = static::$kernel->getContainer();
        }

        return $this->container;
    }

    /**
     * Getter method for auth service
     *
     * @return Auth
     */
    public function getAuthService()
    {
        if (!($this->authService instanceof Auth)) {
            // We need to boot kernel up to get auth service
            self::bootKernel();

            $this->authService = $this->getContainer()->get('app.services.tests.helpers.auth');
        }

        return $this->authService;
    }

    /**
     * Helper method to get authorized client for specified username and password.
     *
     * @param   string  $username
     * @param   string  $password
     * @param   array   $options
     * @param   array   $server
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function getClient(string $username, string $password, array $options = null, array $server = null)
    {
        $options = $options ?? [];
        $server = $server ?? [];

        return static::createClient(
            $options,
            \array_merge(
                $this->getAuthService()->getAuthorizationHeadersForUser($username, $password),
                $server
            )
        );
    }
}
