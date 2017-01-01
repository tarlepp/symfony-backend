<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/EventListener/JWTCreatedListenerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\EventListener;

use App\Entity\User as UserEntity;
use App\EventListener\JWTCreatedListener;
use App\Repository\User as UserRepository;
use App\Services\Rest\User as UserService;
use App\Tests\Helpers\PHPUnitUtil;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class JWTCreatedListenerTest
 *
 * @package AppBundle\unit\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTCreatedListenerTest extends KernelTestCase
{
    public function testThatUserServiceLoadUserByUsernameIsCalled()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|UserService    $userService
         * @var \PHPUnit_Framework_MockObject_MockObject|RoleHierarchy  $roleHierarchy
         * @var \PHPUnit_Framework_MockObject_MockObject|RequestStack   $requestStack
         */
        $userService = $this->createMock(UserService::class);
        $userRepository = $this->createMock(UserRepository::class);
        $userEntity = $this->createMock(UserEntity::class);
        $roleHierarchy = $this->createMock(RoleHierarchy::class);
        $requestStack = $this->createMock(RequestStack::class);
        $jwtCreatedEvent = $this->createMock(JWTCreatedEvent::class);

        $jwtCreatedEvent
            ->expects(static::once())
            ->method('getUser')
            ->willReturn(new \stdClass());

        $userRepository
            ->expects(static::once())
            ->method('loadUserByUsername')
            ->with('fakeUser')
            ->willReturn($userEntity);

        $userService
            ->expects(static::once())
            ->method('getRepository')
            ->willReturn($userRepository);

        $listener = new JWTCreatedListener($userService, $roleHierarchy, $requestStack);

        static::assertInstanceOf(
            UserEntity::class,
            PHPUnitUtil::callMethod($listener, 'getUser', [$jwtCreatedEvent, 'fakeUser'])
        );
    }
}
