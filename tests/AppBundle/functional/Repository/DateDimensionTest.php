<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/DateDimensionTest.php
 *
 * @DateDimension  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\DateDimension as Entity;
use App\Entity\Interfaces\EntityInterface;
use App\Repository\DateDimension as Repository;
use App\Tests\RepositoryTestCase;

/**
 * Class DateDimensionTest
 *
 * @package AppBundle\Entity
 * @DateDimension  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DateDimensionTest extends RepositoryTestCase
{
    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $entityName = Entity::class;

    /**
     * @var bool
     */
    protected $skipUserAssociations = true;

    /**
     * @inheritdoc
     */
    protected function createEntity(EntityInterface $entity = null): EntityInterface
    {
        $entity = new $this->entityName(new \DateTime());

        return parent::createEntity($entity);
    }
}
