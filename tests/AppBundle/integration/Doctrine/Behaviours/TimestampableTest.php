<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Doctrine/Behaviours/TimestampableTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Doctrine\Behaviours;

use App\Entity\User;
use App\Tests\KernelTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class TimestampableTest
 *
 * @package AppBundle\integration\Doctrine\Behaviours
 */
class TimestampableTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var User
     */
    protected $entity;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $entityName = User::class;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        // Store container and entity manager
        $this->container = static::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        /** @var User entity */
        $this->entity = new $this->entityName();

        $this->repository = $this->entityManager->getRepository($this->entityName);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks

        self::$kernel->shutdown();
    }

    public function testThatGetCreatedAtJsonReturnsExpected()
    {
        $this->entity->setCreatedAt(new \DateTime('2016-06-20 18:00:35', new \DateTimeZone('Europe/Helsinki')));

        static::assertSame('2016-06-20T18:00:35+03:00', $this->entity->getCreatedAtJson());

        static::assertSame(
            (new \DateTime('2016-06-20 18:00:35', new \DateTimeZone('Europe/Helsinki')))->format('U'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getCreatedAtJson())->format('U')
        );
    }

    public function testThatGetUpdatedAtJsonReturnsExpected()
    {
        $this->entity->setUpdatedAt(new \DateTime('2016-06-20 18:00:35', new \DateTimeZone('Europe/Helsinki')));

        static::assertSame('2016-06-20T18:00:35+03:00', $this->entity->getUpdatedAtJson());

        static::assertSame(
            (new \DateTime('2016-06-20 18:00:35', new \DateTimeZone('Europe/Helsinki')))->format('U'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getUpdatedAtJson())->format('U')
        );
    }
}
