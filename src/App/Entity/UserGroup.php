<?php
/**
 * /src/App/Entity/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

// Application components
use App\Doctrine\Behaviours as ORMBehaviors;

// Symfony components
use Symfony\Component\Security\Core\Role\RoleInterface;

// Doctrine components
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use JMS\Serializer\Annotation as JMS;

/**
 * Class UserGroup
 *
 * @ORM\Table(
 *      name="user_group",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="uq_role",
 *              columns={"role"}
 *          ),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\UserGroup"
 *  )
 *
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroup extends Base implements RoleInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Group id.
     *
     * @var integer
     *
     * @JMS\Groups({"Default", "UserGroup", "UserGroupId"})
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer"
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Group name
     *
     * @var string
     *
     * @JMS\Groups({"Default", "UserGroup"})
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255
     *  )
     */
    private $name;

    /**
     * Role name
     *
     * @var string
     *
     * @JMS\Groups({"Default", "UserGroup"})
     *
     * @ORM\Column(
     *      name="role",
     *      type="string",
     *      length=20,
     *      unique=true
     *  )
     */
    private $role;

    /**
     * @JMS\Groups({"User", "UserId"})
     *
     * @ORM\ManyToMany(
     *      targetEntity="User",
     *      mappedBy="userGroups"
     *  )
     */
    private $users;

    /**
     * Group constructor.
     *
     * @return  UserGroup
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Getter for group id
     *
     * @return  integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for group name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Setter for group name.
     *
     * @param   string  $name
     *
     * @return  UserGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for role.
     *
     * @param   string  $role
     *
     * @return  UserGroup
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Method to attach new user group to user.
     *
     * @param   User    $user
     *
     * @return  UserGroup
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove specified user from user group.
     *
     * @param   User    $user
     *
     * @return  UserGroup
     */
    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserGroup($this);
        }

        return $this;
    }
}
