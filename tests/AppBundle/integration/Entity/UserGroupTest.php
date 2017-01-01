<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/UserGroupTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\UserGroup;
use App\Tests\EntityTestCase;

/**
 * Class UserGroupTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupTest extends EntityTestCase
{
    /**
     * @var UserGroup
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = UserGroup::class;
}
