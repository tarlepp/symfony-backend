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

    /**
     * @dataProvider dataProviderTestThatPasswordHashingIsWorkingAsExpected
     *
     * @param   callable    $callable
     * @param   string      $password
     * @param   string      $expected
     */
    public function testThatPasswordHashingIsWorkingAsExpected($callable, $password, $expected)
    {
        $this->entity->setPassword($callable, $password);

        $this->assertEquals($expected, $this->entity->getPassword());
    }

    /**
     * Data provider for testThatPasswordHashingIsWorkingAsExpected
     *
     * @return array
     */
    public function dataProviderTestThatPasswordHashingIsWorkingAsExpected()
    {
        return [
            ['str_rot13', 'password', 'cnffjbeq'],
            ['base64_encode', 'password', 'cGFzc3dvcmQ='],
        ];
    }
}
