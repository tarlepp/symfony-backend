<?php
/**
 * /tests/AppBundle/Entity/UserGroupTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\UserGroup;
use App\Tests\EntityTestCase;

/**
 * Class UserGroupTest
 *
 * @package AppBundle\Entity
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
    protected $entityName = 'App\Entity\UserGroup';
}
