<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/DateDimensionTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\DateDimension;
use App\Tests\EntityTestCase;

/**
 * Class DateDimensionTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DateDimensionTest extends EntityTestCase
{
    /**
     * @var DateDimension
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\DateDimension';
}
