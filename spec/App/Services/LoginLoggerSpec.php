<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/LoginLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Entity\User as UserEntity;
use App\Services\Interfaces\LoginLogger as LoginLoggerInterface;
use App\Services\LoginLogger;
use App\Services\Rest\UserLogin as UserLoginService;
use App\Repository\User as UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param   \PhpSpec\Wrapper\Collaborator|UserRepository    $userRepository
     * @param   \PhpSpec\Wrapper\Collaborator|RequestStack      $requestStack
     */
    function let(
        LoggerInterface $logger,
        UserLoginService $userLoginService,
        UserRepository $userRepository,
        RequestStack $requestStack
    ) {
        $this->beConstructedWith($logger, $userLoginService, $userRepository, $requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LoginLogger::class);
        $this->shouldImplement(LoginLoggerInterface::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|UserInterface     $user
     * @param   \PhpSpec\Wrapper\Collaborator|UserRepository    $userRepository
     */
    function it_should_return_expected_value_when_calling_setUser_method_without_user(
        UserInterface $user,
        UserRepository $userRepository
    ) {
        $userRepository->loadUserByUsername(Argument::any())->shouldBeCalled()->willReturn($user);

        $this->setUser($user)->shouldReturn($this);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|UserEntity        $user
     * @param   \PhpSpec\Wrapper\Collaborator|UserRepository    $userRepository
     */
    function it_should_return_expected_value_when_calling_setUser_method_with_user(
        UserEntity $user,
        UserRepository $userRepository
    ) {
        $userRepository->loadUserByUsername(Argument::any())->shouldNotBeCalled();

        $this->setUser($user)->shouldReturn($this);
    }
}
