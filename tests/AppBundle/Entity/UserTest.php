<?php
/**
 * /tests/AppBundle/Entity/UserTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\User;
use App\Tests\EntityTestCase;

/**
 * Class UserTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserTest extends EntityTestCase
{
    /**
     * @var User
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\User';
}
