<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/UserLoginTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\UserLogin;
use App\Tests\EntityTestCase;

/**
 * Class UserLoginTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserLoginTest extends EntityTestCase
{
    /**
     * @var UserLogin
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\UserLogin';
}
