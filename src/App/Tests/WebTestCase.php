<?php
/**
 * /src/App/Tests/WebTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Tests\Helpers\Auth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as Base;

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
     * Getter method for auth service
     *
     * @return Auth
     */
    public function getAuthService()
    {
        if (!($this->authService instanceof Auth)) {
            // We need to boot kernel up to get auth service
            self::bootKernel();

            $this->authService = static::$kernel->getContainer()->get('app.services.tests.helpers.auth');
        }

        return $this->authService;
    }
}
