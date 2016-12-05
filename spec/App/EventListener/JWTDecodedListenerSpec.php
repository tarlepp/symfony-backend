<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/JWTDecodedListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\JWTDecodedListener;
use PhpSpec\ObjectBehavior;
use App\Services\Rest\User as UserService;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTDecodedListenerSpec
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTDecodedListenerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|RequestStack  $requestStack
     * @param   \PhpSpec\Wrapper\Collaborator|UserService   $userService
     */
    function let(
        RequestStack $requestStack,
        UserService $userService
    ) {
        $this->beConstructedWith($requestStack, $userService);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JWTDecodedListener::class);
    }
}
