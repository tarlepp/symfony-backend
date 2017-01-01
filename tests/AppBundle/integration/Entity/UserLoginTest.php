<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/UserLoginTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\UserLogin;
use App\Tests\EntityTestCase;

/**
 * Class UserLoginTest
 *
 * @package AppBundle\integration\Entity
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
    protected $entityName = UserLogin::class;
}
