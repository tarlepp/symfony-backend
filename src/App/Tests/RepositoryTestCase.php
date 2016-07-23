<?php
/**
 * /src/App/Tests/RepositoryTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Entity\Interfaces\EntityInterface;
use App\Tests\Helpers\PHPUnitUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\OrderBy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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

    /**
     * Method to test that 'getReference' method returns expected object.
     */
    public function testThatGetReferenceReturnsExpected()
    {
        /** @var EntityInterface $entity */
        $entity = new $this->entityName();

        $this->assertInstanceOf(
            '\Doctrine\Common\Proxy\Proxy',
            $this->repository->getReference($entity->getId())
        );
    }

    /**
     * Method to test that 'getAssociations' method return an array.
     */
    public function testThatGetAssociationsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->repository->getAssociations());
    }

    /**
     * @dataProvider dataProviderTestThatProcessCriteriaCreatesExpectedCondition
     *
     * @param   array   $criteria
     * @param   string  $expectedDQL
     */
    public function testThatProcessCriteriaCreatesExpectedCondition(array $criteria, $expectedDQL)
    {
        $queryBuilder = $this->repository->createQueryBuilder('e');

        PHPUnitUtil::callMethod($this->repository, 'processCriteria', [$queryBuilder, $criteria]);

        $this->assertEquals(
            sprintf($expectedDQL, $this->entityName),
            $queryBuilder->getQuery()->getDQL(),
            'processCriteria method did not create expected query criteria.'
        );
    }

    /**
     * @dataProvider DataProviderTestThatProcessOrderByCreatesExpectedOrderByClause
     *
     * @param   array   $orderBy
     * @param   string  $expectedResult
     */
    public function testThatProcessOrderByCreatesExpectedOrderByClause(array $orderBy, string $expectedResult)
    {
        $queryBuilder= $this->repository->createQueryBuilder('e');

        PHPUnitUtil::callMethod($this->repository, 'processOrderBy', [$queryBuilder, $orderBy]);

        $iterator = function (OrderBy $orderBy) {
            return (string)$orderBy;
        };

        $this->assertEquals(
            $expectedResult,
            implode(', ', array_map($iterator, $queryBuilder->getDQLPart('orderBy'))),
            'processOrderBy method did not create expected query WHERE part.'
        );
    }

    /**
     * Data provider for 'testThatProcessCriteriaCreatesExpectedCondition'
     *
     * @return array
     */
    public function dataProviderTestThatProcessCriteriaCreatesExpectedCondition()
    {
        return [
            [
                ['e.id' => 1],
                'SELECT e FROM %s e WHERE e.id = ?1'
            ],
            [
                ['e.id' => [1,2,3]],
                'SELECT e FROM %s e WHERE e.id IN(1, 2, 3)'
            ],
            [
                [
                    'e.foo' => 'foo',
                    'e.bar' => 'bar'
                ],
                'SELECT e FROM %s e WHERE e.foo = ?1 AND e.bar = ?2'
            ],
            [
                [
                    'e.foo' => ['foo', 'bar'],
                    'e.bar' => ['bar', 'foo']
                ],
                "SELECT e FROM %s e WHERE e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo')"
            ],
            [
                [
                    'e.id' => 1,
                    'e.foo' => ['foo', 'bar'],
                    'e.bar' => ['bar', 'foo']
                ],
                "SELECT e FROM %s e WHERE e.id = ?1 AND e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo')"
            ],
            [
                [
                    'id' => 1,
                    'foo' => ['foo', 'bar'],
                    'bar' => ['bar', 'foo'],
                ],
                "SELECT e FROM %s e WHERE entity.id = ?1 AND entity.foo IN('foo', 'bar') AND entity.bar IN('bar', 'foo')"
            ],
            [
                [
                    'id' => 1,
                    'and' => [
                        ['e.foo', 'in', ['foo', 'bar']],
                        ['e.bar', 'in', ['bar', 'foo']],
                    ],
                ],
                "SELECT e FROM %s e WHERE entity.id = ?1 AND (e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo'))"
            ],
            [
                [
                    'id' => 1,
                    'or' => [
                        ['e.foo', 'in', ['foo', 'bar']],
                        ['e.bar', 'in', ['bar', 'foo']],
                    ],
                ],
                "SELECT e FROM %s e WHERE entity.id = ?1 AND (e.foo IN('foo', 'bar') OR e.bar IN('bar', 'foo'))"
            ],
        ];
    }

    /**
     * Data provider for 'testThatProcessOrderByCreatesExpectedOrderByClause'
     *
     * @return array
     */
    function DataProviderTestThatProcessOrderByCreatesExpectedOrderByClause()
    {
        return [
            [
                [
                    'e.id' => 'ASC',
                ],
                'e.id ASC',
            ],
            [
                [
                    'id' => 'ASC',
                ],
                'entity.id ASC',
            ],
            [
                [
                    'id'    => 'ASC',
                    'foo'   => 'DESC',
                ],
                'entity.id ASC, entity.foo DESC',
            ],
            [
                [
                    'e.id'  => 'ASC',
                    'b.foo' => 'DESC',
                ],
                'e.id ASC, b.foo DESC',
            ],
            [
                [
                    'e.id'  => 'ASC',
                    'b.foo' => 'DESC',
                    'f.bar' => 'ASC',
                ],
                'e.id ASC, b.foo DESC, f.bar ASC',
            ],
        ];
    }
}
