<?php
declare(strict_types = 1);
/**
 * /spec/App/EventListener/ResponseListenerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\EventListener;

use App\EventListener\ResponseListener;
use App\Services\Interfaces\ResponseLogger;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
}
