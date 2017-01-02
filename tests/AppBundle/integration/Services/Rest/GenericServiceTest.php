<?php
declare(strict_types=1);
/**
 * /tests/AppBundle/integration/Services/Rest/GenericServiceTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Services\Rest;

use App\Entity\Interfaces\EntityInterface;
use App\Repository\Base as Repository;
use App\Services\Rest\User as UserService;
use App\Entity\User as UserEntity;
use Doctrine\Common\Proxy\Proxy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class GenericServiceTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GenericServiceTest extends KernelTestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        // Store container and entity manager
        $this->container = static::$kernel->getContainer();

        $this->validator = $this->container->get('validator');
    }

    public function testThatGetEntityNameCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('getEntityName');

        $repository
            ->expects(static::once())
            ->method('getEntityName')
            ->with()
            ->willReturn('fake entity name');

        $service = new UserService($repository, $this->validator);
        $service->getEntityName();
    }

    public function testThatGetReferenceCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('getReference');
        $proxy = $this->createMock(Proxy::class);

        $repository
            ->expects(static::once())
            ->method('getReference')
            ->with('entity_id')
            ->willReturn($proxy);

        $service = new UserService($repository, $this->validator);
        $service->getReference('entity_id');
    }

    public function testThatGetRepositoryReturnsExpected()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);
        $service = new UserService($repository, $this->validator);

        static::assertEquals($repository, $service->getRepository());
    }

    public function testThatGetAssociationsCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('getAssociations');

        $repository
            ->expects(static::once())
            ->method('getAssociations')
            ->with()
            ->willReturn([]);

        $service = new UserService($repository, $this->validator);
        $service->getAssociations();
    }

    public function testThatFindCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('findByWithSearchTerms');

        $repository
            ->expects(static::once())
            ->method('findByWithSearchTerms')
            ->with(['search', 'words'], ['some' => 'criteria'], ['-order'], 10, 20)
            ->willReturn([]);

        $service = new UserService($repository, $this->validator);
        $service->find(['some' => 'criteria'], ['-order'], 10, 20, ['search', 'words']);
    }

    public function testThatFindOneCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('find');

        $entity = $this->createMock(EntityInterface::class);

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn($entity);

        $service = new UserService($repository, $this->validator);
        $service->findOne('entityId');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatFindOneThrowsAnExceptionIfEntityWasNotFoundAndThrowParameterIsSet()
    {
        $repository = $this->getRepositoryMock('find');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);
        $service->findOne('entityId', true);
    }

    public function testThatFindOneDoesNotThrowAnExceptionIfEntityWasNotFoundAndThrowParameterIsNotSet()
    {
        $repository = $this->getRepositoryMock('find');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);

        static::assertNull($service->findOne('entityId', false));
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatFindOneByThrowsAnExceptionIfEntityWasNotFoundAndThrowParameterIsSet()
    {
        $repository = $this->getRepositoryMock('findOneBy');

        $repository
            ->expects(static::once())
            ->method('findOneBy')
            ->with(['foo' => 'bar'], ['-bar'])
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);
        $service->findOneBy(['foo' => 'bar'], ['-bar'], true);
    }

    public function testThatFindOneByDoesNotThrowAnExceptionIfEntityWasNotFoundAndThrowParameterIsNotSet()
    {
        $repository = $this->getRepositoryMock('findOneBy');

        $repository
            ->expects(static::once())
            ->method('findOneBy')
            ->with(['foo' => 'bar'], ['-bar'])
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);

        static::assertNull($service->findOneBy(['foo' => 'bar'], ['-bar'], false));
    }

    public function testThatCountCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('count');

        $repository
            ->expects(static::once())
            ->method('count')
            ->with(['foo' => 'bar'], ['search', 'terms'])
            ->willReturn(10);

        $service = new UserService($repository, $this->validator);

        static::assertEquals(10, $service->count(['foo' => 'bar'], ['search', 'terms']));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testThatCreateThrowsAnException()
    {
        $repository = $this->getRepositoryMock('getClassName', 'save');

        $repository
            ->expects(static::once())
            ->method('getClassName')
            ->willReturn(UserEntity::class);

        $repository
            ->expects(static::never())
            ->method('save');

        $service = new UserService($repository, $this->validator);
        $service->create(new \stdClass());
    }

    public function testThatCreateCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('getClassName', 'save');

        $repository
            ->expects(static::once())
            ->method('getClassName')
            ->willReturn(UserEntity::class);

        $object = new \stdClass();
        $object->username = 'foo.bar';
        $object->firstname = 'foo';
        $object->surname = 'bar';
        $object->email = 'foobar@foobar.com';

        $service = new UserService($repository, $this->validator);
        $service->create($object);
    }

    public function testThatSaveCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|EntityInterface|UserEntity $entity */
        $entity = $this->createMock(EntityInterface::class);

        $repository = $this->getRepositoryMock('save');

        $repository
            ->expects(static::once())
            ->method('save')
            ->with($entity);

        $service = new UserService($repository, $this->validator);
        $service->save($entity);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testThatSaveThrowsAnExceptionIfEntityIsNotValid()
    {
        $entity = new UserEntity();

        $repository = $this->getRepositoryMock('save');

        $repository
            ->expects(static::never())
            ->method('save');

        $service = new UserService($repository, $this->validator);
        $service->save($entity);
    }

    public function testThatSaveDoesNotThrowExceptionIfSkipValidationIsSet()
    {
        $entity = new UserEntity();

        $repository = $this->getRepositoryMock('save');

        $repository
            ->expects(static::once())
            ->method('save')
            ->with($entity);

        $service = new UserService($repository, $this->validator);
        $service->save($entity, true);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatUpdateThrowsAnExceptionIfEntityIsNotFound()
    {
        $repository = $this->getRepositoryMock('find');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('id-that-does-not-exists')
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);
        $service->update('id-that-does-not-exists', new \stdClass());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testThatUpdateThrowsAnExceptionWithInvalidEntity()
    {
        $entity = new UserEntity();

        $repository = $this->getRepositoryMock('find');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('invalid-entity')
            ->willReturn($entity);

        $service = new UserService($repository, $this->validator);
        $service->update('invalid-entity', new \stdClass());
    }

    public function testThatUpdateReturnsExpectedData()
    {
        $entity = new UserEntity();
        $entity->setUsername('foo.bar');
        $entity->setFirstname('foo');
        $entity->setSurname('bar');
        $entity->setEmail('foo.bar@foobar.com');

        $expectedEntity = clone $entity;
        $expectedEntity->setUsername('bar.foo');

        $repository = $this->getRepositoryMock('find', 'save');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entity-id')
            ->willReturn($entity);

        $repository
            ->expects(static::once())
            ->method('save')
            ->willReturn($repository);

        $service = new UserService($repository, $this->validator);

        static::assertEquals($expectedEntity, $service->update('entity-id', (object)['username' => 'bar.foo']));
    }

    public function testThatUpdateIgnoresCertainProperties()
    {
        $entity = new UserEntity();
        $entity->setUsername('foo.bar');
        $entity->setFirstname('foo');
        $entity->setSurname('bar');
        $entity->setEmail('foo.bar@foobar.com');

        $repository = $this->getRepositoryMock('find', 'save');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entity-id')
            ->willReturn($entity);

        $repository
            ->expects(static::once())
            ->method('save')
            ->willReturn($repository);

        $service = new UserService($repository, $this->validator);

        $entity = $service->update('entity-id', (object)['password' => 'foobar']);

        static::assertNull($entity->getPassword());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatDeleteThrowsAnExceptionIfEntityIsNotFound()
    {
        $repository = $this->getRepositoryMock('find');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('id-that-does-not-exists')
            ->willReturn(null);

        $service = new UserService($repository, $this->validator);
        $service->delete('id-that-does-not-exists');
    }

    public function testThatDeleteCallsServiceMethods()
    {
        $entity = new UserEntity();

        $repository = $this->getRepositoryMock('find', 'remove');

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entity-id')
            ->willReturn($entity);

        $repository
            ->expects(static::once())
            ->method('remove')
            ->with($entity)
            ->willReturn($repository);

        $service = new UserService($repository, $this->validator);
        $service->delete('entity-id');
    }

    public function testThatGetIdsCallsServiceMethods()
    {
        $repository = $this->getRepositoryMock('findIds');

        $repository
            ->expects(static::once())
            ->method('findIds')
            ->with(['foo' => 'bar'], ['search', 'terms'])
            ->willReturn(['', '', '']);

        $service = new UserService($repository, $this->validator);

        static::assertEquals(['', '', ''], $service->getIds(['foo' => 'bar'], ['search', 'terms']));
    }

    /**
     * @param   array   $methods
     *
     * @return  Repository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRepositoryMock(...$methods)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods($methods)
            ->getMock();

        return $repository;
    }
}
