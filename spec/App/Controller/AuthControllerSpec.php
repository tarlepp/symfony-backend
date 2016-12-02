<?php
/**
 * /spec/App/Controller/AuthControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\AuthController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use JMS\Serializer\SerializerInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use PhpSpec\Wrapper\Subject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthControllerSpec
 *
 * @mixin   AuthController
 *
 * @package spec\App\Controller
 */
class AuthControllerSpec extends ObjectBehavior
{
    /**
     * @param   Collaborator|TokenStorageInterface $tokenStorage
     * @param   Collaborator|SerializerInterface $serializer
     * @param   Collaborator|RestHelperResponseInterface $restHelperResponse
     */
    function let(
        TokenStorageInterface $tokenStorage,
        SerializerInterface $serializer,
        RestHelperResponseInterface $restHelperResponse
    ) {
        $this->beConstructedWith(
            $tokenStorage,
            $serializer,
            $restHelperResponse
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AuthController::class);
    }

    function it_should_response_null_to_getTokenAction()
    {
        /** @var Subject $result */
        $result = $this->getTokenAction();

        $result->shouldReturn(null);
    }
}
