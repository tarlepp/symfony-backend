<?php
/**
 * /src/App/Entity/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserGroup
 *
 * @AssertCollection\UniqueEntity("role")
 *
 * @ORM\Table(
 *      name="user_group",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uq_role", columns={"role"}),
 *      },
 *      indexes={
 *          @ORM\Index(name="createdBy_id", columns={"createdBy_id"}),
 *          @ORM\Index(name="updatedBy_id", columns={"updatedBy_id"}),
 *          @ORM\Index(name="deletedBy_id", columns={"deletedBy_id"})
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
class UserGroup implements EntityInterface, RoleInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Group id.
     *
     * @var integer
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserGroup",
     *      "UserGroups",
     *      "User.userGroups",
     *      "UserGroup.id",
     *  })
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer"
     *  )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Group name.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserGroup",
     *      "UserGroups",
     *      "UserGroup.name",
     *  })
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 4, max = 255)
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $name;

    /**
     * Role name.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserGroup",
     *      "UserGroups",
     *      "UserGroup.role",
     *  })
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 20)
     *
     * @ORM\Column(
     *      name="role",
     *      type="string",
     *      length=20,
     *      nullable=false,
     *      unique=true
     *  )
     */
    private $role;

    /**
     * Array collection of users of group.
     *
     * @var ArrayCollection
     *
     * @JMS\Groups({
     *      "Users",
     *      "UserGroup.users",
     *  })
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
     * Getter for user collection.
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
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
