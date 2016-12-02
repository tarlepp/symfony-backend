<?php
declare(strict_types = 1);
/**
 * /spec/App/Controller/DefaultControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Controller;

use App\Controller\DefaultController;
use PhpSpec\ObjectBehavior;

/**
 * Class DefaultControllerSpec
 *
 * @mixin DefaultController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultController::class);
    }
}
