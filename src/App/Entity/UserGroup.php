<?php
declare(strict_types=1);
/**
 * /src/App/Entity/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
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
 *          @ORM\Index(name="created_by_id", columns={"created_by_id"}),
 *          @ORM\Index(name="updated_by_id", columns={"updated_by_id"}),
 *          @ORM\Index(name="deleted_by_id", columns={"deleted_by_id"})
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\UserGroup"
 *  )
 *
 * @JMS\XmlRoot("userGroup")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroup implements EntityInterface, RoleInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Group id.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "UserGroup",
     *      "UserGroup.id",
     *      "User.userGroups",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="id",
     *      type="guid",
     *      nullable=false
     *  )
     * @ORM\Id()
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
     *      "UserGroup.name",
     *  })
     * @JMS\Type("string")
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
     *      "UserGroup.role",
     *  })
     * @JMS\Type("string")
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
     * @var ArrayCollection|User[]
     *
     * @JMS\Groups({
     *      "Users",
     *      "UserGroup.users",
     *  })
     * @JMS\Type("ArrayCollection<App\Entity\User>")
     *
     * @ORM\ManyToMany(
     *      targetEntity="User",
     *      mappedBy="userGroups",
     *      cascade={"all"},
     *  )
     */
    private $users;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();

        $this->users = new ArrayCollection();
    }

    /**
     * Getter for group id
     *
     * @return  string
     */
    public function getId(): string
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
     * @return Collection|ArrayCollection<User>
     */
    public function getUsers(): Collection
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
    public function setName(string $name): UserGroup
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
    public function setRole(string $role): UserGroup
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
    public function addUser(User $user): UserGroup
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
    public function removeUser(User $user): UserGroup
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove all many-to-many user relations from current user group.
     *
     * @return  UserGroup
     */
    public function clearUsers(): UserGroup
    {
        $this->users->clear();

        return $this;
    }
}
