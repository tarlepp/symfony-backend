<?php
/**
 * /spec/App/Controller/AuthorControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\AuthorController;
use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

/**
 * Class AuthorControllerSpec
 *
 * @mixin AuthorController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(AuthorController::class);
        $this->shouldImplement(RestController::class);
    }
}
