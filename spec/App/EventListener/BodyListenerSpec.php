<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/BodyListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\BodyListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class BodyListenerSpec
 *
 * @mixin BodyListener
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BodyListenerSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|GetResponseEvent    $event
     * @param \PhpSpec\Wrapper\Collaborator|Request             $request
     * @param \PhpSpec\Wrapper\Collaborator|ParameterBag        $parameterBag
     */
    function let(
        GetResponseEvent $event,
        Request $request,
        ParameterBag $parameterBag
    ) {
        // Mock getRequest
        $event->getRequest()->willReturn($request);

        // Mock necessary stuff within $request
        $request->getContent(Argument::any())->willReturn('');
        $request->getWrappedObject()->request = $parameterBag;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BodyListener::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|GetResponseEvent  $event
     * @param   \PhpSpec\Wrapper\Collaborator|Request           $request
     */
    function it_should_not_do_anything_if_request_content_is_empty(
        GetResponseEvent $event,
        Request $request
    ) {
        $request->request->replace()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
