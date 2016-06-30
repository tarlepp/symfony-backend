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

    public function testThatSetPlainPasswordIsWorkingAsExpected()
    {
        // First set new password
        $this->entity->setPassword('str_rot13', 'password');

        // Set plain password
        $this->entity->setPlainPassword('plainPassword');

        $this->assertEmpty($this->entity->getPassword());
        $this->assertEquals('plainPassword', $this->entity->getPlainPassword());
    }

    public function testThatSetEmptyPlainPasswordDoesNotResetPassword()
    {
        // First set new password
        $this->entity->setPassword('str_rot13', 'password');

        // Set plain password
        $this->entity->setPlainPassword('');

        $this->assertNotEmpty($this->entity->getPassword());
        $this->assertEmpty($this->entity->getPlainPassword());
    }

    public function testThatUserEntityCanBeSerializedAndUnSerializedAsExpected()
    {
        // First set some data for entity
        $this->entity->setUsername('john');
        $this->entity->setPassword('str_rot13', 'password');

        /** @var User $entity */
        $entity = unserialize(serialize($this->entity));

        // Assert that unserialized object returns expected data
        
        $this->assertEquals('john', $entity->getUsername());
        $this->assertEquals('cnffjbeq', $entity->getPassword());
    }

    public function testThatGetUserGroupsReturnsInstanceOfArrayCollection()
    {
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $this->entity->getUserGroups());
    }

    public function testThatGetRolesReturnsAnArray()
    {
        $this->assertInternalType('array', $this->entity->getRoles());
    }

    public function testThatGetSaltMethodReturnsNull()
    {
        $this->assertNull($this->entity->getSalt());
    }

    public function testThatGetLoginDataMethodReturnsExpected()
    {
        $expected = [
            'firstname',
            'surname',
            'email',
        ];

        foreach ($expected as $key) {
            $method = 'set' . ucfirst($key);

            call_user_func([$this->entity, $method], $key);
        }

        $data = $this->entity->getLoginData();

        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($key, $data[$key]);
        }
    }

    public function testThatEraseCredentialsMethodWorksAsExpected()
    {
        $this->entity->setPlainPassword('password');

        $this->entity->eraseCredentials();

        $this->assertEmpty($this->entity->getPlainPassword());
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
