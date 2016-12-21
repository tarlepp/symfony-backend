<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Repository/DateDimensionTest.php
 *
 * @DateDimension  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Repository;

use App\Entity\DateDimension as Entity;
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
    protected $entityName = 'App\Entity\DateDimension';
}
