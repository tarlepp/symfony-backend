<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/UserGroupControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Tests\Traits\TestThatBaseRouteWithAnonUserReturns401;
use App\Tests\WebTestCase;

/**
 * Class UserGroupControllerTest
 *
 * @category    Tests
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupControllerTest extends WebTestCase
{
    static protected $baseRoute = '/user_group';

    use TestThatBaseRouteWithAnonUserReturns401;
}
