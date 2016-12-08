<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/LoginLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Services\Interfaces\LoginLogger as LoginLoggerInterface;
use App\Services\LoginLogger;
use App\Services\Rest\UserLogin as UserLoginService;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class LoginLoggerSpec
 *
 * @package spec\App\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class LoginLoggerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface   $logger
     * @param   \PhpSpec\Wrapper\Collaborator|UserLoginService  $userLoginService
     * @param   \PhpSpec\Wrapper\Collaborator|EntityRepository  $userRepository
     * @param   \PhpSpec\Wrapper\Collaborator|RequestStack      $requestStack
     */
    function let(
        LoggerInterface $logger,
        UserLoginService $userLoginService,
        EntityRepository $userRepository,
        RequestStack $requestStack
    ) {
        $this->beConstructedWith($logger, $userLoginService, $userRepository, $requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LoginLogger::class);
        $this->shouldImplement(LoginLoggerInterface::class);
    }
}
