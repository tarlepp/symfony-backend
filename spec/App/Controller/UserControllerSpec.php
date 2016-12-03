<?php
/**
 * /spec/App/Controller/UserControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\UserController;
use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

/**
 * Class UserControllerSpec
 *
 * @mixin UserController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserControllerSpec extends ObjectBehavior
{
    /**
     * @param   Collaborator|ResourceServiceInterface    $resourceService
     * @param   Collaborator|RestHelperResponseInterface $restHelperResponse
     */
    public function let(
        ResourceServiceInterface $resourceService,
        RestHelperResponseInterface $restHelperResponse
    ) {
        $restHelperResponse->setResourceService($resourceService);

        $this->beConstructedWith(
            $resourceService,
            $restHelperResponse
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserController::class);
        $this->shouldImplement(RestController::class);
    }
}
