<?php
/**
 * /src/App/Entity/Group.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

// Symfony components
use Symfony\Component\Security\Core\Role\RoleInterface;

// Doctrine components
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use JMS\Serializer\Annotation as JMS;

/**
 * Class Group
 *
 * @ORM\Table(
 *      name="group",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="uq_role",
 *              columns={"role"}
 *          ),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Group"
 *  )
 *
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Group implements RoleInterface
{
    /**
     * Group id.
     *
     * @var integer
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer"
     * )
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Group name
     *
     * @var string
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
     * @ORM\Column(
     *      name="role",
     *      type="string",
     *      length=20,
     *      unique=true
     *  )
     */
    private $role;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="User",
     *      mappedBy="groups"
     *  )
     */
    private $users;

    /**
     * Group constructor.
     *
     * @return  Group
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
     * @return  Group
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
     * @return  Group
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}
