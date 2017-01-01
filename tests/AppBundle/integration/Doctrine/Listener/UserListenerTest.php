<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Doctrine/Listener/UserListenerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Doctrine\Listener;

use App\Doctrine\Listener\UserListener;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class UserListenerTest
 *
 * @package AppBundle\integration\Doctrine\Listener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserListenerTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var User
     */
    protected $entity;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var UserListener
     */
    protected $listener;

    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        // Store container and entity manager
        $this->container = static::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        // Create listener
        $this->listener = new UserListener($this->container->get('security.encoder_factory'));

        // Create new user but not store it at this time
        $this->entity = new User();
        $this->entity->setUsername('john_doe_the_tester');
        $this->entity->setEmail('john.doe_the_tester@test.com');
        $this->entity->setFirstname('John');
        $this->entity->setSurname('Doe');

        // Get used encoder
        $this->encoder = $this->container->get('security.encoder_factory')->getEncoder($this->entity);
    }

    public function tearDown()
    {
        if ($this->entityManager->contains($this->entity)) {
            $this->entityManager->remove($this->entity);
            $this->entityManager->flush();
        }

        static::$kernel->shutdown();

        parent::tearDown();
    }

    public function testThatGetEncoderReturnsExpected()
    {
        static::assertInstanceOf(
            PasswordEncoderInterface::class,
            $this->listener->getEncoder($this->entity)
        );
    }

    public function testListenerPrePersistMethodWorksAsExpected()
    {
        // Get store old password
        $oldPassword = $this->entity->getPassword();

        // Set plain password so that listener can make a real one
        $this->entity->setPlainPassword('test');

        // Create event for prePersist method
        $event = new LifecycleEventArgs($this->entity, $this->entityManager);

        // Call listener method
        $this->listener->prePersist($event);

        static::assertEmpty(
            $this->entity->getPlainPassword(),
            'Listener did not reset plain password value.'
        );

        static::assertNotEquals(
            $oldPassword,
            $this->entity->getPassword(),
            'Password was not changed by the listener.'
        );

        static::assertTrue(
            $this->encoder->isPasswordValid($this->entity->getPassword(), 'test', ''),
            'Changed password is not valid.'
        );
    }

    public function testListenerPreUpdateMethodWorksAsExpected()
    {
        $encoder = $this->encoder;

        $callable = function ($password) use ($encoder) {
            return $encoder->encodePassword($password, '');
        };

        // Create encrypted password manually for user
        $this->entity->setPassword($callable, 'test');

        // Set plain password so that listener can make a real one
        $this->entity->setPlainPassword('test');

        // Get store old password
        $oldPassword = $this->entity->getPassword();

        $changeSet = [];

        $event = new PreUpdateEventArgs($this->entity, $this->entityManager, $changeSet);

        $this->listener->preUpdate($event);

        static::assertEmpty(
            $this->entity->getPlainPassword(),
            'Listener did not reset plain password value.'
        );

        static::assertNotEquals(
            $oldPassword,
            $this->entity->getPassword(),
            'Password was not changed by the listener.'
        );

        static::assertTrue(
            $this->encoder->isPasswordValid($this->entity->getPassword(), 'test', ''),
            'Changed password is not valid.'
        );
    }
}
