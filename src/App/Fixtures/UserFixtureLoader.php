<?php
/**
 * /src/App/Fixtures/UserFixtureLoader.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtureLoader
 *
 * @category    Fixtures
 * @package     App\Fixtures
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserFixtureLoader implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('john');
        $user->setFirstname('John');
        $user->setSurname('Doe');
        $user->setEmail('john.doe@test.com');
        $user->setPlainPassword('doe');

        $manager->persist($user);
        $manager->flush();
    }
}
