<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/BookControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Tests\Traits\TestThatBaseRouteWithAnonUserReturns401;
use App\Tests\WebTestCase;

/**
 * Class BookControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookControllerTest extends WebTestCase
{
    static protected $baseRoute = '/author';

    use TestThatBaseRouteWithAnonUserReturns401;
}
