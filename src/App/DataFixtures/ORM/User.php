<?php
/**
 * /src/App/DataFixtures/ORM/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DataFixtures\ORM;

use App\Entity\User as UserEntity;
use App\Entity\UserGroup as UserGroupEntity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class User
 *
 * This fixture will create following data to test environment database:
 * -
 *  id: 1
 *  username: "john-logged"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-logged@test.com"
 *  password: "$2y$12$YhoZ4RTSYtzsN2z22zS7Wu3TPuzKTXotkSK/btoLqw09WrsWoCRoe"
 * -
 *  id: 2
 *  username: "john-user"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-user@test.com"
 *  password: "$2y$12$HMuprf24Ew6I4z58BGYDY.qsG1YKwkQesI7a47iUWXLQn4y6Jsf2C"
 * -
 *  id: 3
 *  username: "john-admin"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-admin@test.com"
 *  password: "$2y$12$3Zewr/ZVD//xq5QgBXRpiekmexCHXI6N.8tBlTtExbsly7e9acA3K"
 * -
 *  id: 4
 *  username: "john-root"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe-root@test.com"
 *  password: "$2y$12$ZS.tAjKrQwqsWKRsl7FpnO5OUBc6bTiQ2NMtyVaDPqd4VSmdHXHVK"
 * -
 *  id: 5
 *  username: "john"
 *  firstname: "John"
 *  surname: "Doe"
 *  email: "john.doe@test.com"
 *  password: "$2y$12$CsfxsAnRCQ3n51eXRWkLiu5YHc7UhWWrfirNFQphL5PD1MAy53Apm"
 *
 * Also note that users with username 'john-{user_group_role}' has also added to that specified user group.
 *
 * Passwords for these users are just 'doe' OR 'doe-{user_group_role}' depending on which user group he belongs.
 *
 * @category    Fixtures
 * @package     App\DataFixtures\ORM
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    // Traits
    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roles = $this->container->get('app.services.helper.roles');

        // Iterate each roles and create user to each of those
        foreach ($roles->getRoles() as $role) {
            $short = $roles->getShort($role);

            // Create new user
            $user = new UserEntity();
            $user->setUsername('john-' . $short);
            $user->setFirstname('John');
            $user->setSurname('Doe');
            $user->setEmail('john.doe-' . $short . '@test.com');
            $user->setPlainPassword('doe-' . $short);

            /** @var UserGroupEntity $userGroup */
            $userGroup = $this->getReference('user-group-' . $short);
            $user->addUserGroup($userGroup);

            $manager->persist($user);

            // Create reference to current user
            $this->addReference('user-' . $user->getUsername(), $user);
        }

        // And finally create user that has no roles at all
        $user = new UserEntity();
        $user->setUsername('john');
        $user->setFirstname('John');
        $user->setSurname('Doe');
        $user->setEmail('john.doe@test.com');
        $user->setPlainPassword('doe');

        // Create reference to current user
        $this->addReference('user-' . $user->getUsername(), $user);

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
