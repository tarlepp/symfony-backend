<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/UserTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\User;
use App\Tests\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class UserTest
 *
 * @package AppBundle\integration\Entity
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
    protected $entityName = User::class;

    /**
     * @dataProvider dataProviderTestThatPasswordHashingIsWorkingAsExpected
     *
     * @param   callable    $callable
     * @param   string      $password
     * @param   string      $expected
     */
    public function testThatPasswordHashingIsWorkingAsExpected(callable $callable, string $password, string $expected)
    {
        $this->entity->setPassword($callable, $password);

        static::assertEquals($expected, $this->entity->getPassword());
    }

    public function testThatSetPlainPasswordIsWorkingAsExpected()
    {
        // First set new password
        $this->entity->setPassword('str_rot13', 'password');

        // Set plain password
        $this->entity->setPlainPassword('plainPassword');

        static::assertEmpty($this->entity->getPassword());
        static::assertEquals('plainPassword', $this->entity->getPlainPassword());
    }

    public function testThatSetEmptyPlainPasswordDoesNotResetPassword()
    {
        // First set new password
        $this->entity->setPassword('str_rot13', 'password');

        // Set plain password
        $this->entity->setPlainPassword('');

        static::assertNotEmpty($this->entity->getPassword());
        static::assertEmpty($this->entity->getPlainPassword());
    }

    public function testThatUserEntityCanBeSerializedAndUnSerializedAsExpected()
    {
        // First set some data for entity
        $this->entity->setUsername('john');
        $this->entity->setPassword('str_rot13', 'password');

        /** @var User $entity */
        $entity = unserialize(serialize($this->entity), [\stdClass::class]);

        // Assert that unserialized object returns expected data

        static::assertEquals('john', $entity->getUsername());
        static::assertEquals('cnffjbeq', $entity->getPassword());
    }

    public function testThatGetUserGroupsReturnsInstanceOfArrayCollection()
    {
        static::assertInstanceOf(ArrayCollection::class, $this->entity->getUserGroups());
    }

    public function testThatGetRolesReturnsAnArray()
    {
        static::assertInternalType('array', $this->entity->getRoles());
    }

    public function testThatGetSaltMethodReturnsNull()
    {
        static::assertNull($this->entity->getSalt());
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

            $this->entity->{$method}($key);
        }

        $data = $this->entity->getLoginData();

        foreach ($expected as $key) {
            static::assertArrayHasKey($key, $data);
            static::assertEquals($key, $data[$key]);
        }
    }

    public function testThatEraseCredentialsMethodWorksAsExpected()
    {
        $this->entity->setPlainPassword('password');

        $this->entity->eraseCredentials();

        static::assertEmpty($this->entity->getPlainPassword());
    }

    /**
     * @dataProvider dataProviderTestThatIsEqualToMethodWorksAsExpected
     *
     * @param bool $expected
     */
    public function testThatIsEqualToMethodWorksAsExpected(bool $expected)
    {
        $entity = $expected ? clone $this->entity : new $this->entityName();

        $message = 'Failed to check if User entity is equal.';

        static::assertEquals($expected, $this->entity->isEqualTo($entity), $message);
    }

    /**
     * Data provider for testThatPasswordHashingIsWorkingAsExpected
     *
     * @return array
     */
    public function dataProviderTestThatPasswordHashingIsWorkingAsExpected(): array
    {
        return [
            ['str_rot13', 'password', 'cnffjbeq'],
            ['base64_encode', 'password', 'cGFzc3dvcmQ='],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatIsEqualToMethodWorksAsExpected(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
