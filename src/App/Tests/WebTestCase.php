<?php
declare(strict_types=1);
/**
 * /src/App/Tests/WebTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Tests\Helpers\Auth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as Base;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class WebTestCase extends Base
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
    public function getClient(string $username, string $password, array $options = [], array $server = [])
    {
        return static::createClient(
            $options,
            array_merge(
                $this->getAuthService()->getAuthorizationHeadersForUser($username, $password),
                $server
            )
        );
    }
}
