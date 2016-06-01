<?php
/**
 * Created by PhpStorm.
 * User: wunder
 * Date: 1.6.2016
 * Time: 19:08
 */

namespace App\Util\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as Base;

abstract class WebTestCase extends Base
{
    /**
     * @var Auth
     */
    private $authService;

    /**
     * @return Auth
     */
    public function getAuthService()
    {
        if (!($this->authService instanceof Auth)) {
            // We need to boot kernel up to get Entity Manager
            self::bootKernel();

            $this->authService = static::$kernel->getContainer()->get('app.services.utils.tests.auth');
        }

        return $this->authService;
    }
}
