<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/JWTCreatedListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\JWTCreatedListener;
use App\Services\Rest\User as UserService;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class JWTCreatedListenerSpec
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTCreatedListenerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|UserService   $userService
     * @param   \PhpSpec\Wrapper\Collaborator|RoleHierarchy $roleHierarchy
     * @param   \PhpSpec\Wrapper\Collaborator|RequestStack  $requestStack
     */
    function let(
        UserService $userService,
        RoleHierarchy $roleHierarchy,
        RequestStack $requestStack
    ) {
        $this->beConstructedWith($userService, $roleHierarchy, $requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JWTCreatedListener::class);
    }
}
