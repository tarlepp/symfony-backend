<?php
declare(strict_types = 1);
/**
 * /src/App/DataFixtures/ORM/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DataFixtures\ORM;

use App\Entity\User as UserEntity;
use App\Entity\UserGroup as UserGroupEntity;
use App\Services\Helper\Interfaces\Roles;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class User
 *
 * This fixture will create following data to test environment database, also note that some values change on each
 * fixture load:
 * -
 *  id: UUID_V4
 *  username: "john-logged"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-logged@test.com"
 *  password: ENCRYPTED_PASSWORD
 * -
 *  id: UUID_V4
 *  username: "john-user"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-user@test.com"
 *  password: ENCRYPTED_PASSWORD
 * -
 *  id: UUID_V4
 *  username: "john-admin"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-admin@test.com"
 *  password: ENCRYPTED_PASSWORD
 * -
 *  id: UUID_V4
 *  username: "john-root"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-root@test.com"
 *  password: ENCRYPTED_PASSWORD
 * -
 *  id: UUID_V4
 *  username: "john"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe@test.com"
 *  password: ENCRYPTED_PASSWORD
 *
 * Also note that users with username 'john-{user_group_role}' has also added to that specified user group.
 *
 * Passwords for these users are just 'doe' OR 'doe-{user_group_role}' depending on which user group he belongs.
 *
 * @package App\DataFixtures\ORM
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    // Traits
    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleService = $this->container->get('app.services.helper.roles');

        $roles = $roleService->getRoles();

        // Create users for every user group / role
        array_map(
            [$this, 'createUser'],
            array_fill(0, count($roles), $manager),
            array_fill(0, count($roles), $roleService),
            $roles
        );

        // And finally create user without group / role
        $this->createUser($manager, $roleService);

        // Flush all changes to database
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Helper method to create actual user entities.
     *
     * @param   ObjectManager   $manager
     * @param   Roles           $roles
     * @param   string|null     $role
     */
    private function createUser(ObjectManager $manager, Roles $roles, string $role = null)
    {
        $suffix = null === $role ? '' : '-' . $roles->getShort($role);

        // Create new user
        $user = new UserEntity();
        $user->setUsername('john' . $suffix);
        $user->setFirstname('John');
        $user->setSurname('Doe');
        $user->setEmail('john.doe' . $suffix . '@test.com');
        $user->setPlainPassword('doe' . $suffix);

        if (null !== $role) {
            /** @var UserGroupEntity $userGroup */
            $userGroup = $this->getReference('user-group-' . $roles->getShort($role));
            $user->addUserGroup($userGroup);
        }

        $manager->persist($user);

        // Create reference to current user
        $this->addReference('user-' . $user->getUsername(), $user);
    }
}
