<?php
/**
 * /spec/App/Controller/UserGroupControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\UserGroupController;
use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

/**
 * Class UserGroupControllerSpec
 *
 * @mixin UserGroupController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(UserGroupController::class);
        $this->shouldImplement(RestController::class);
    }
}
