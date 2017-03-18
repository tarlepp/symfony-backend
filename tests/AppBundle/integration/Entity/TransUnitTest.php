<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/TransUnitTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\TransUnit;
use App\Tests\EntityTestCase;

/**
 * Class TransUnitTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TransUnitTest extends EntityTestCase
{
    /**
     * @var TransUnit
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = TransUnit::class;
}
