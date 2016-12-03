<?php
/**
 * /spec/App/Controller/BookControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\BookController;
use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;

/**
 * Class BookControllerSpec
 *
 * @mixin BookController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(BookController::class);
        $this->shouldImplement(RestController::class);
    }
}
