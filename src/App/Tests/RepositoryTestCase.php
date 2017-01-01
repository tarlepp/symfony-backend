<?php
declare(strict_types=1);
/**
 * /src/App/Tests/RepositoryTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Entity\Interfaces\EntityInterface;
use App\Repository\Base as Repository;
use App\Tests\Helpers\PHPUnitUtil;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class RepositoryTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class RepositoryTestCase extends KernelTestCase
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
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var \App\Repository\Base
     */
    protected $repository;

    /**
     * @var array
     */
    protected $associations = [];

    /**
     * @var bool
     */
    protected $skipUserAssociations = false;

    /**
     * @var array
     */
    protected $entityProperties = [];

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

        // Get repository
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

    public function testThatGetEntityNameReturnsExpected()
    {
        static::assertEquals($this->entityName, $this->repository->getEntityName());
    }

    /**
     * Method to test that 'getReference' method returns expected object.
     */
    public function testThatGetReferenceReturnsExpected()
    {
        /** @var EntityInterface $entity */
        $entity = new $this->entityName();

        static::assertInstanceOf(
            Proxy::class,
            $this->repository->getReference($entity->getId())
        );
    }

    public function testThatGetAssociationsReturnsExpected()
    {
        $message = 'Repository did not return expected associations for entity.';

        static::assertEquals(
            array_merge(
                $this->associations,
                $this->skipUserAssociations ? [] : ['createdBy', 'updatedBy', 'deletedBy']
            ),
            array_keys($this->repository->getAssociations()),
            $message
        );
    }

    public function testThatSaveMethodCallsExpectedServices()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|EntityInterface $entityInterface */
        $entityInterface = $this->createMock(EntityInterface::class);

        // Create mock for entity manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Check that 'persist' method is called
        $entityManager
            ->expects(static::once())
            ->method('persist')
            ->with($entityInterface);

        // Check that 'flush' method is called
        $entityManager
            ->expects(static::once())
            ->method('flush');

        $repositoryClass = get_class($this->repository);

        /** @var Repository $repository */
        $repository = new $repositoryClass($entityManager, new ClassMetadata($this->entityName));

        // Call save method
        $repository->save($entityInterface);
    }

    public function testThatRemoveMethodCallsExpectedServices()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|EntityInterface $entityInterface */
        $entityInterface = $this->createMock(EntityInterface::class);

        // Create mock for entity manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Check that 'persist' method is called
        $entityManager
            ->expects(static::once())
            ->method('remove')
            ->with($entityInterface);

        // Check that 'flush' method is called
        $entityManager
            ->expects(static::once())
            ->method('flush');

        $repositoryClass = get_class($this->repository);

        /** @var Repository $repository */
        $repository = new $repositoryClass($entityManager, new ClassMetadata($this->entityName));

        // Call remove method
        $repository->remove($entityInterface);
    }

    /**
     * @expectedException \Doctrine\ORM\Query\QueryException
     */
    public function testThatCountMethodThrowsAnExceptionWithInvalidCriteria()
    {
        $this->repository->count(['foo' => 'bar']);
    }

    public function testThatCountMethodReturnsExpectedValue()
    {
        static::assertEquals(0, $this->repository->count(['id' => 'foobar']));
    }

    /**
     * @expectedException \Doctrine\ORM\Query\QueryException
     */
    public function testThatFindByWithSearchTermsMethodThrowsAnExceptionWithInvalidCriteria()
    {
        $this->repository->findByWithSearchTerms([], ['foo' => 'bar']);
    }

    public function testThatFindByWithSearchTermsMethodReturnsExpectedValue()
    {
        static::assertEquals([], $this->repository->findByWithSearchTerms([], ['id' => 'foobar']));
    }

    /**
     * @expectedException \Doctrine\ORM\Query\QueryException
     */
    public function testThatFindIdsMethodThrowsAnExceptionWithInvalidCriteria()
    {
        $this->repository->findIds(['foo' => 'bar'], []);
    }

    public function testThatFindIdsMethodReturnsExpectedValue()
    {
        static::assertEquals(0, $this->repository->count(['id' => 'foobar']));
    }

    public function testThatResetMethodCleansTable()
    {
        if ($this->repository->count() === 0) {
            $this->createEntity();
        }

        $this->repository->reset();

        static::assertEquals(0, $this->repository->count());

        $this->resetDatabase();
    }

    /**
     * @dataProvider dataProviderTestThatProcessSearchTermsCreatesExpectedCriteria
     *
     * @param   array   $searchTerms
     * @param   string  $operand
     * @param   string  $expectedDQL
     */
    public function testThatProcessSearchTermsCreatesExpectedCriteria(
        array $searchTerms,
        string $operand,
        string $expectedDQL
    ) {
        if (count($this->repository->getSearchColumns()) === 0) {
            $message = sprintf(
                "Repository for entity '%s' doesn't contain any defined search columns.",
                $this->entityName
            );

            static::markTestSkipped($message);
        }

        $queryBuilder= $this->repository->createQueryBuilder('e');

        PHPUnitUtil::callMethod($this->repository, 'processSearchTerms', [$queryBuilder, [$operand => $searchTerms]]);

        $criteria = [];
        $index = 1;

        $iterator = function ($column) use (&$index) {
            if (strpos($column, '.') === false) {
                $column = 'entity.' . $column;
            }

            $output = sprintf(
                '%s LIKE ?%d',
                $column,
                $index
            );

            $index++;

            return $output;
        };

        $iMax = count($searchTerms);

        for ($i = 0; $i < $iMax; $i++) {
            $criteria = array_merge($criteria, array_map($iterator, $this->repository->getSearchColumns()));
        }

        static::assertEquals(
            sprintf($expectedDQL, $this->entityName, implode(' ' . strtoupper($operand) . ' ', $criteria)),
            $queryBuilder->getQuery()->getDQL(),
            'processSearchTerms method did not create expected query criteria.'
        );
    }

    /**
     * Data provider for 'testThatProcessSearchTermsCreatesExpectedCriteria' method.
     *
     * @return array
     */
    public function dataProviderTestThatProcessSearchTermsCreatesExpectedCriteria()
    {
        return [
            [
                [],
                'or',
                /** @lang text */
                'SELECT e FROM %s e'
            ],
            [
                ['foo'],
                'or',
                /** @lang text */
                'SELECT e FROM %s e WHERE %s'
            ],
            [
                ['foo', 'bar'],
                'or',
                /** @lang text */
                'SELECT e FROM %s e WHERE %s'
            ],
            [
                [],
                'and',
                /** @lang text */
                'SELECT e FROM %s e'
            ],
            [
                ['foo'],
                'and',
                /** @lang text */
                'SELECT e FROM %s e WHERE %s'
            ],
            [
                ['foo', 'bar'],
                'and',
                /** @lang text */
                'SELECT e FROM %s e WHERE %s'
            ],
        ];
    }

    /**
     * @param   EntityInterface $entity
     *
     * @return  EntityInterface
     */
    protected function createEntity(EntityInterface $entity = null): EntityInterface
    {
        if (null === $entity && count($this->entityProperties) === 0) {
            static::markTestSkipped();
        }

        if (null === $entity) {
            $entity = new $this->entityName();

            foreach ($this->entityProperties as $property => $value) {
                $method = 'set' . ucfirst($property);

                $entity->{$method}($value);
            }
        }

        $this->repository->save($entity);

        return $entity;
    }

    /**
     * Helper method to reset database state to original one.
     */
    private function resetDatabase()
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);


        // Add the doctrine:fixtures:load command to the application and run it
        $loadFixturesDoctrineCommand = function () use ($application) {
            $command = new LoadDataFixturesDoctrineCommand();
            $application->add($command);

            $input = new ArrayInput([
                'command' => 'doctrine:fixtures:load',
                '--no-interaction' => true,
            ]);

            $input->setInteractive(false);

            $command->run($input, new NullOutput());
        };

        array_map(
            'call_user_func',
            [
                $loadFixturesDoctrineCommand,
            ]
        );
    }
}
