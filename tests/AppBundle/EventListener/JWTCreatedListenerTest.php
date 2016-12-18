<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/EventListener/JWTCreatedListenerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\EventListener;

use App\Entity\User as UserEntity;
use App\EventListener\JWTCreatedListener;
use App\Repository\User as UserRepository;
use App\Services\Rest\User as UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class JWTCreatedListenerTest
 *
 * @package AppBundle\EventListener
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
            ->with('fakeuser')
            ->willReturn($userEntity);

        $userService
            ->expects(static::once())
            ->method('getRepository')
            ->willReturn($userRepository);

        $listener = new JWTCreatedListener($userService, $roleHierarchy, $requestStack);

        static::assertInstanceOf(UserEntity::class, $this->invokeMethod($listener, 'getUser', [$jwtCreatedEvent, 'fakeuser']));
    }

    /**
     * Call protected/private method of a class.
     *
     * @param   object  $object     Instantiated object that we will run method on.
     * @param   string  $methodName Method name to call
     * @param   array   $parameters Array of parameters to pass into method.
     *
     * @return  mixed Method return.
     */
    private function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
