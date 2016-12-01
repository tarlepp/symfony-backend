<?php
declare(strict_types = 1);

namespace spec\App\Controller;

use App\Controller\AuthController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use JMS\Serializer\SerializerInterface;
use PhpSpec\ObjectBehavior;
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
     * @param   TokenStorageInterface       $tokenStorage
     * @param   SerializerInterface         $serializer
     * @param   RestHelperResponseInterface $restHelperResponse
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
}
