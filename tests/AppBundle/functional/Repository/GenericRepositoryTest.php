<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/GenericRepositoryTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\Interfaces\EntityInterface;
use App\Tests\Helpers\PHPUnitUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\OrderBy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use App\Entity\User;

/**
 * Class GenericRepositoryTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GenericRepositoryTest extends KernelTestCase
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
    protected $entityName = User::class;

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
     * @dataProvider dataProviderTestThatProcessCriteriaCreatesExpectedCondition
     *
     * @param   array   $criteria
     * @param   string  $expectedDQL
     */
    public function testThatProcessCriteriaCreatesExpectedCondition(array $criteria, $expectedDQL)
    {
        $queryBuilder = $this->repository->createQueryBuilder('e');

        PHPUnitUtil::callMethod($this->repository, 'processCriteria', [$queryBuilder, $criteria]);

        static::assertEquals(
            sprintf($expectedDQL, $this->entityName),
            $queryBuilder->getQuery()->getDQL(),
            'processCriteria method did not create expected query criteria.'
        );
    }

    /**
     * @dataProvider dataProviderTestThatProcessOrderByCreatesExpectedOrderByClause
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

        static::assertEquals(
            $expectedResult,
            implode(', ', array_map($iterator, $queryBuilder->getDQLPart('orderBy'))),
            'processOrderBy method did not create expected query ORDER BY part.'
        );
    }

    /**
     * Data provider for 'testThatProcessCriteriaCreatesExpectedCondition'
     *
     * @return array
     */
    public function dataProviderTestThatProcessCriteriaCreatesExpectedCondition(): array
    {
        return [
            [
                ['e.id' => 1],
                <<<QUERY
SELECT e FROM %s e WHERE e.id = ?1
QUERY
            ],
            [
                ['e.id' => [1,2,3]],
                <<<QUERY
SELECT e FROM %s e WHERE e.id IN(1, 2, 3)
QUERY
            ],
            [
                [
                    'e.foo' => 'foo',
                    'e.bar' => 'bar'
                ],
                <<<QUERY
SELECT e FROM %s e WHERE e.foo = ?1 AND e.bar = ?2
QUERY
            ],
            [
                [
                    'e.foo' => ['foo', 'bar'],
                    'e.bar' => ['bar', 'foo']
                ],
                <<<QUERY
SELECT e FROM %s e WHERE e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo')
QUERY
            ],
            [
                [
                    'e.id' => 1,
                    'e.foo' => ['foo', 'bar'],
                    'e.bar' => ['bar', 'foo']
                ],
                <<<QUERY
SELECT e FROM %s e WHERE e.id = ?1 AND e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo')
QUERY
            ],
            [
                [
                    'id' => 1,
                    'foo' => ['foo', 'bar'],
                    'bar' => ['bar', 'foo'],
                ],
                <<<QUERY
SELECT e FROM %s e WHERE entity.id = ?1 AND entity.foo IN('foo', 'bar') AND entity.bar IN('bar', 'foo')
QUERY
            ],
            [
                [
                    'id' => 1,
                    'and' => [
                        ['e.foo', 'in', ['foo', 'bar']],
                        ['e.bar', 'in', ['bar', 'foo']],
                    ],
                ],
                <<<QUERY
SELECT e FROM %s e WHERE entity.id = ?1 AND (e.foo IN('foo', 'bar') AND e.bar IN('bar', 'foo'))
QUERY
            ],
            [
                [
                    'id' => 1,
                    'or' => [
                        ['e.foo', 'in', ['foo', 'bar']],
                        ['e.bar', 'in', ['bar', 'foo']],
                    ],
                ],
                <<<QUERY
SELECT e FROM %s e WHERE entity.id = ?1 AND (e.foo IN('foo', 'bar') OR e.bar IN('bar', 'foo'))
QUERY
            ],
        ];
    }

    /**
     * Data provider for 'testThatProcessOrderByCreatesExpectedOrderByClause'
     *
     * @return array
     */
    public function dataProviderTestThatProcessOrderByCreatesExpectedOrderByClause(): array
    {
        return [
            [
                [],
                '',
            ],
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
