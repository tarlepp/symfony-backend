<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/AuthenticationSuccessListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\AuthenticationSuccessListener;
use App\Services\Interfaces\LoginLogger;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationSuccessListenerSpec
 *
 * @mixin AuthenticationSuccessListener
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthenticationSuccessListenerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|AuthenticationSuccessEvent    $event
     * @param   \PhpSpec\Wrapper\Collaborator|LoginLogger                   $loginLogger
     * @param   \PhpSpec\Wrapper\Collaborator|UserInterface                 $user
     */
    function let(
        AuthenticationSuccessEvent $event,
        LoginLogger $loginLogger,
        UserInterface $user
    ) {
        // Mock getUser method
        $event->getUser()->willReturn($user);

        // Mock setUser method
        $loginLogger->setUser($user)->willReturn($loginLogger);

        $this->beConstructedWith($loginLogger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticationSuccessListener::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|AuthenticationSuccessEvent    $event
     * @param   \PhpSpec\Wrapper\Collaborator|LoginLogger                   $loginLogger
     * @param   \PhpSpec\Wrapper\Collaborator|UserInterface                 $user
     */
    function it_should_call_LoginLogger_handle_method(
        AuthenticationSuccessEvent $event,
        LoginLogger $loginLogger,
        UserInterface $user
    ) {
        $event->getUser()->shouldBeCalled();
        $loginLogger->setUser($user)->shouldBeCalled();
        $loginLogger->handle()->shouldBeCalled();

        $this->onAuthenticationSuccess($event);
    }
}
