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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Class GenericServiceTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GenericServiceTest extends KernelTestCase
{
    public function testThatGetEntityNameCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('getEntityName')
            ->with()
            ->willReturn('fake entity name');

        $service = new UserService($repository, Validation::createValidator());
        $service->getEntityName();
    }

    public function testThatGetReferenceCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('getReference')
            ->with('entity_id');

        $service = new UserService($repository, Validation::createValidator());
        $service->getReference('entity_id');
    }

    public function testThatGetRepositoryReturnsExpected()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);
        $service = new UserService($repository, Validation::createValidator());

        static::assertEquals($repository, $service->getRepository());
    }

    public function testThatGetAssociationsCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('getAssociations')
            ->with()
            ->willReturn([]);

        $service = new UserService($repository, Validation::createValidator());
        $service->getAssociations();
    }

    public function testThatFindCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('findByWithSearchTerms')
            ->with(['search', 'words'], ['some' => 'criteria'], ['-order'], 10, 20)
            ->willReturn([]);

        $service = new UserService($repository, Validation::createValidator());
        $service->find(['some' => 'criteria'], ['-order'], 10, 20, ['search', 'words']);
    }

    public function testThatFindOneCallsServiceMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);
        $entity = $this->createMock(EntityInterface::class);

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn($entity);

        $service = new UserService($repository, Validation::createValidator());
        $service->findOne('entityId');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testThatFindOneThrowsAnExceptionIfEntityWasNotFoundAndThrowParameterIsSet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn(null);

        $service = new UserService($repository, Validation::createValidator());
        $service->findOne('entityId', true);
    }

    public function testThatFindOneDoesNotThrowAnExceptionIfEntityWasNotFoundAndThrowParameterIsNotSet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('find')
            ->with('entityId')
            ->willReturn(null);

        $service = new UserService($repository, Validation::createValidator());

        static::assertNull($service->findOne('entityId', false));
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testThatFindOneByThrowsAnExceptionIfEntityWasNotFoundAndThrowParameterIsSet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('findOneBy')
            ->with(['foo' => 'bar'], ['-bar'])
            ->willReturn(null);

        $service = new UserService($repository, Validation::createValidator());
        $service->findOneBy(['foo' => 'bar'], ['-bar'], true);
    }

    public function testThatFindOneByDoesNotThrowAnExceptionIfEntityWasNotFoundAndThrowParameterIsNotSet()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $repository */
        $repository = $this->createMock(Repository::class);

        $repository
            ->expects(static::once())
            ->method('findOneBy')
            ->with(['foo' => 'bar'], ['-bar'])
            ->willReturn(null);

        $service = new UserService($repository, Validation::createValidator());

        static::assertNull($service->findOneBy(['foo' => 'bar'], ['-bar'], false));
    }
}
