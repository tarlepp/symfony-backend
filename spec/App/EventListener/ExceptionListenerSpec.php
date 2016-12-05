<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/ExceptionListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\ExceptionListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListenerSpec
 *
 * @mixin ExceptionListener
 *
 * @package spec\App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionListenerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface               $logger
     * @param   \PhpSpec\Wrapper\Collaborator|GetResponseForExceptionEvent  $event
     */
    function let(
        LoggerInterface $logger,
        GetResponseForExceptionEvent $event
    ) {
        $this->beConstructedWith($logger, 'dev');

        $exception = new \Exception('Error message');

        $event->getException()->willReturn($exception);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ExceptionListener::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface               $logger
     * @param   \PhpSpec\Wrapper\Collaborator|GetResponseForExceptionEvent  $event
     */
    function it_should_log_exception(
        LoggerInterface $logger,
        GetResponseForExceptionEvent $event
    ) {
        $logger->error(Argument::any(), Argument::any())->shouldBeCalled();
        $event->setResponse(Argument::type(Response::class))->shouldBeCalled();

        $this->onKernelException($event);
    }
}
