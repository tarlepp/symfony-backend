<?php
declare(strict_types = 1);
/**
 * /src/App/DataFixtures/ORM/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DataFixtures\ORM;

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
 * Class UserGroup
 *
 * This fixture will create following data to test environment database, also note that id value will change on each
 * time you load fixtures:
 *
 * -
 *  id: UUID_V4
 *  name: "Logged in users"
 *  role: "ROLE_LOGGED"
 * -
 *  id: UUID_V4
 *  name: "Normal users"
 *  role: "ROLE_USER"
 * -
 *  id: UUID_V4
 *  name: "Admin users"
 *  role: "ROLE_ADMIN"
 * -
 *  id: UUID_V4
 *  name: "Root users"
 *  role: "ROLE_ROOT"
 *
 * @package App\DataFixtures\ORM
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroup extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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

        // Create groups
        array_map(
            [$this, 'createGroup'],
            array_fill(0, count($roles), $manager),
            array_fill(0, count($roles), $roleService),
            $roles
        );

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
        return 0;
    }

    /**
     * Helper method to create actual group entities.
     *
     * @param   ObjectManager   $manager
     * @param   Roles           $roleService
     * @param   string          $role
     */
    private function createGroup(ObjectManager $manager, Roles $roleService, string $role)
    {
        // Create new user group
        $group = new UserGroupEntity();
        $group->setName($roleService->getRoleLabel($role));
        $group->setRole($role);

        $manager->persist($group);

        // Create reference to current user group
        $this->addReference('user-group-' . $roleService->getShort($role), $group);
    }
}
