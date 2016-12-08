<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/LoginLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Entity\User as UserEntity;
use App\Entity\UserLogin as UserLoginEntity;
use App\Services\Interfaces\LoginLogger as LoginLoggerInterface;
use App\Services\LoginLogger;
use App\Services\Rest\UserLogin as UserLoginService;
use App\Repository\User as UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
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
    function it_should_fetch_user_from_repository_when_calling_setUser_method_with_UserInterface(
        UserInterface $user,
        UserRepository $userRepository
    ) {
        $userRepository->loadUserByUsername(Argument::any())->shouldBeCalled()->willReturn($user);

        $this->setUser($user);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|UserEntity        $user
     * @param   \PhpSpec\Wrapper\Collaborator|UserRepository    $userRepository
     */
    function it_should_not_fetch_user_from_repository_when_calling_setUser_method_with_UserEntity(
        UserEntity $user,
        UserRepository $userRepository
    ) {
        $userRepository->loadUserByUsername(Argument::any())->shouldNotBeCalled();

        $this->setUser($user);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|RequestStack      $requestStack
     * @param   \PhpSpec\Wrapper\Collaborator|Request           $request
     * @param   \PhpSpec\Wrapper\Collaborator|HeaderBag         $headerBag
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface   $logger
     * @param   \PhpSpec\Wrapper\Collaborator|UserEntity        $user
     * @param   \PhpSpec\Wrapper\Collaborator|UserLoginService  $userLoginService
     * @param   \PhpSpec\Wrapper\Collaborator|UserLoginEntity   $userLogin
     */
    function it_should_log_user_login_as_expected(
        RequestStack $requestStack,
        Request $request,
        HeaderBag $headerBag,
        LoggerInterface $logger,
        UserEntity $user,
        UserLoginService $userLoginService,
        UserLoginEntity $userLogin
    ) {
        // Mock user-agent get
        $headerBag->get('User-Agent')->shouldBeCalled()->willReturn('fake-user-agent');

        // Mock necessary things in request
        $request->headers = $headerBag;
        $request->getClientIp()->willReturn('fake ip');
        $request->getHost()->willReturn('fake host');

        // Attach mocked request to request stack
        $requestStack->getCurrentRequest()->willReturn($request);

        // Check that user login save is called
        $userLoginService->save(Argument::type(UserLoginEntity::class))->shouldBeCalled()->willReturn($userLogin);

        // Logger debug method should be called
        $logger->debug('Created new login entry to database.')->shouldBeCalled();

        // And do everything necessary
        $this->setUser($user);
        $this->handle();
    }
}
