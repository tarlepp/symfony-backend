<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/ResponseListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\Entity\User;
use App\EventListener\ResponseListener;
use App\Services\Interfaces\ResponseLogger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ResponseListenerSpec
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseListenerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|ResponseLogger        $responseLogger
     * @param   \PhpSpec\Wrapper\Collaborator|TokenStorageInterface $tokenStorage
     */
    function let(
        ResponseLogger $responseLogger,
        TokenStorageInterface $tokenStorage
    ) {
        $this->beConstructedWith($responseLogger, $tokenStorage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseListener::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|FilterResponseEvent   $event
     * @param   \PhpSpec\Wrapper\Collaborator|Request               $request
     * @param   \PhpSpec\Wrapper\Collaborator|ResponseLogger        $responseLogger
     */
    function it_should_not_do_anything_if_request_method_is_OPTIONS(
        FilterResponseEvent $event,
        Request $request,
        ResponseLogger $responseLogger
    ) {
        // Mock getters
        $event->getRequest()->willReturn($request);
        $request->getRealMethod()->willReturn('OPTIONS');

        // Check that any of ResponseLogger methods are not called
        $responseLogger->setRequest(Argument::type(Request::class))->shouldNotBeCalled();
        $responseLogger->setResponse(Argument::type(Response::class))->shouldNotBeCalled();
        $responseLogger->setUser(Argument::type(UserInterface::class))->shouldNotBeCalled();
        $responseLogger->setMasterRequest(Argument::type('bool'))->shouldNotBeCalled();
        $responseLogger->handle()->shouldNotBeCalled();

        $this->onKernelResponse($event);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|FilterResponseEvent   $event
     * @param   \PhpSpec\Wrapper\Collaborator|Request               $request
     * @param   \PhpSpec\Wrapper\Collaborator|Response              $response
     * @param   \PhpSpec\Wrapper\Collaborator|ResponseLogger        $responseLogger
     * @param   \PhpSpec\Wrapper\Collaborator|TokenStorageInterface $tokenStorage
     */
    function it_should_not_call_TokenStorage_getToken_getUser_method(
        FilterResponseEvent $event,
        Request $request,
        Response $response,
        ResponseLogger $responseLogger,
        TokenStorageInterface $tokenStorage
    ) {
        // Mock getters
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $event->isMasterRequest()->willReturn(true);

        // Expect that tokenStorage->getToken returns null (anonymous user)
        $tokenStorage->getToken()->shouldBeCalled()->willReturn(null);

        // Expect that any of methods are not called
        $responseLogger->setRequest(Argument::type(Request::class))->shouldBeCalled();
        $responseLogger->setResponse(Argument::type(Response::class))->shouldBeCalled();
        $responseLogger->setUser(null)->shouldBeCalled();
        $responseLogger->setMasterRequest((bool)Argument::type('bool'))->shouldBeCalled();
        $responseLogger->handle()->shouldBeCalled();

        $this->onKernelResponse($event);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|FilterResponseEvent   $event
     * @param   \PhpSpec\Wrapper\Collaborator|Request               $request
     * @param   \PhpSpec\Wrapper\Collaborator|Response              $response
     * @param   \PhpSpec\Wrapper\Collaborator|ResponseLogger        $responseLogger
     * @param   \PhpSpec\Wrapper\Collaborator|TokenStorageInterface $tokenStorage
     */
    function it_should_call_TokenStorage_getToken_getUser_method(
        FilterResponseEvent $event,
        Request $request,
        Response $response,
        ResponseLogger $responseLogger,
        TokenStorageInterface $tokenStorage
    ) {
        // Mock getters
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $event->isMasterRequest()->willReturn(true);

        // Create user and token
        $user = new User();
        $token = new UsernamePasswordToken($user, null, 'main', ['ROLE_USER']);

        // Expect that getToken method is called
        $tokenStorage->getToken()->shouldBeCalled()->willReturn($token);

        // Expect that all of necessary methods are called
        $responseLogger->setRequest(Argument::type(Request::class))->shouldBeCalled();
        $responseLogger->setResponse(Argument::type(Response::class))->shouldBeCalled();
        $responseLogger->setUser($user)->shouldBeCalled();
        $responseLogger->setMasterRequest((bool)Argument::type('bool'))->shouldBeCalled();
        $responseLogger->handle()->shouldBeCalled();

        $this->onKernelResponse($event);
    }
}
