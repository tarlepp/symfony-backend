<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/UserTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\User as Entity;
use App\Entity\User;
use App\Repository\User as Repository;
use App\Tests\RepositoryTestCase;
use Symfony\Component\Security\Core\User\User as CoreUser;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserTest extends RepositoryTestCase
{
    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $entityName = User::class;

    /**
     * @var array
     */
    protected $associations = [
        'userGroups',
        'userLogins',
        'userRequests',
    ];

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     * @expectedExceptionMessage Instance of "Symfony\Component\Security\Core\User\User" is not supported.
     */
    public function testThatRefreshUserMethodThrowsAnException()
    {
        $user = new CoreUser('john-doe', 'password');

        $this->repository->refreshUser($user);
    }

    /**
     * @dataProvider dataProviderTestThatRefreshUserMethodLoadsUser
     *
     * @param   User    $user
     * @param   string  $username
     */
    public function testThatRefreshUserMethodLoadsUser(User $user, string $username)
    {
        $message = "Method did not return expected user, weird.";

        static::assertEquals(
            $this->repository->findOneBy(['username' => $username]),
            $this->repository->refreshUser($user),
            $message
        );
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage User "this-user-does-not-exists" not found
     */
    public function testThatLoadUserByUsernameThrowsUsernameNotFoundException()
    {
        $user = (new User())->setUsername('this-user-does-not-exists');

        $this->repository->refreshUser($user);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatRefreshUserMethodLoadsUser(): array
    {
        return [
            [
                (new User())->setUsername('john-logged'),
                'john-logged',
            ],
            [
                (new User())->setUsername('john.doe-admin@test.com'),
                'john-admin',
            ],
        ];
    }
}
