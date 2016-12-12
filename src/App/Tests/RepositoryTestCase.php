<?php
declare(strict_types=1);
/**
 * /src/App/Tests/RepositoryTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Entity\Interfaces\EntityInterface;
use App\Tests\Helpers\PHPUnitUtil;
use Doctrine\ORM\EntityManager;
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

        static::assertInstanceOf(
            '\Doctrine\Common\Proxy\Proxy',
            $this->repository->getReference($entity->getId())
        );
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

        for ($i = 0; $i < count($searchTerms); $i++) {
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
}
