<?php
/**
 * /src/App/Entity/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

// Application components
use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;

// Doctrine components
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

// Symfony components
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertCollection;

// 3rd party components
use JMS\Serializer\Annotation as JMS;

/**
 * Class User
 *
 * @AssertCollection\UniqueEntity("email")
 * @AssertCollection\UniqueEntity("username")
 *
 * @ORM\Table(
 *      name="user",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uq_username", columns={"username"}),
 *          @ORM\UniqueConstraint(name="uq_email", columns={"email"})
 *      },
 *      indexes={
 *          @ORM\Index(name="createdBy_id", columns={"createdBy_id"}),
 *          @ORM\Index(name="updatedBy_id", columns={"updatedBy_id"}),
 *          @ORM\Index(name="deletedBy_id", columns={"deletedBy_id"})
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\User"
 *  )
 *
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User implements EntityInterface, UserInterface, \Serializable
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * User id.
     *
     * @var integer
     *
     * @JMS\Groups({"Default", "User", "CreatedBy", "UpdatedBy", "UserId"})
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false
     *  )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * User's username.
     *
     * @var string
     *
     * @JMS\Groups({"Default", "User"})
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="username",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $username;

    /**
     * User's firstname.
     *
     * @var string
     *
     * @JMS\Groups({"Default", "User"})
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="firstname",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $firstname;

    /**
     * User's surname.
     *
     * @var string
     *
     * @JMS\Groups({"Default", "User"})
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="surname",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $surname;

    /**
     * User's email.
     *
     * @var string
     *
     * @JMS\Groups({"Default", "User"})
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     *
     * @ORM\Column(
     *      name="email",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $email;

    /**
     * User's password (encrypted).
     *
     * @var string
     *
     * @JMS\Exclude
     *
     * @ORM\Column(
     *      name="password",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var  string
     */
    private $plainPassword;

    /**
     * Collection of user's user groups.
     *
     * @var ArrayCollection
     *
     * @JMS\Groups({"UserGroup", "UserGroupId"})
     *
     * @ORM\ManyToMany(
     *     targetEntity="UserGroup",
     *     inversedBy="users"
     *  )
     */
    private $userGroups;

    /**
     * User constructor.
     *
     * return User
     */
    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Getter for firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Getter for surname.
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Getter for email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Getter for password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Getter for plain password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Getter for roles.
     *
     * @return string[]
     */
    public function getRoles()
    {
        /**
         * Lambda iterator to get user group role information.
         *
         * @param   UserGroup   $userGroup
         *
         * @return  string
         */
        $iterator = function($userGroup) {
            return $userGroup->getRole();
        };

        return array_map($iterator, $this->userGroups->toArray());
    }

    /**
     * Getter for user groups collection.
     *
     * @return ArrayCollection
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * Setter for username.
     *
     * @param   string  $username
     *
     * @return  User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Setter for firstname.
     *
     * @param   string  $firstname
     *
     * @return  User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Setter for surname.
     *
     * @param   string  $surname
     *
     * @return  User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Setter for email.
     *
     * @param   string  $email
     *
     * @return  User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Setter for password.
     *
     * @param   string  $password   Note that this must be encoded at this point!
     *
     * @return  User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Setter for plain password.
     *
     * @param   string  $plainPassword
     *
     * @return  User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        // Change some mapped values so preUpdate will get called.
        $this->password = ''; // just blank it out

        return $this;
    }

    /**
     * String representation of object
     *
     * @link    http://php.net/manual/en/serializable.serialize.php
     *
     * @return  string  the string representation of the object or null
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * Constructs the object
     *
     * @link    http://php.net/manual/en/serializable.unserialize.php
     *
     * @param   string  $serialized The string representation of the object.
     *
     * @return  void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Method to get login data for JWT token.
     *
     * Note that roles does not contain possible child role information!
     *
     * @return array
     */
    public function getLoginData()
    {
        return [
            'firstname' => $this->getFirstname(),
            'surname'   => $this->getSurname(),
            'email'     => $this->getEmail(),
            'roles'     => array_unique($this->getRoles()),
        ];
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    /**
     * Method to attach new user group to user.
     *
     * @param   UserGroup   $userGroup
     *
     * @return  User
     */
    public function addUserGroup(UserGroup $userGroup)
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups->add($userGroup);
            $userGroup->addUser($this);
        }

        return $this;
    }

    /**
     * Method to remove specified user group from user.
     *
     * @param   UserGroup   $userGroup
     *
     * @return  User
     */
    public function removeUserGroup(UserGroup $userGroup)
    {
        if ($this->userGroups->contains($userGroup)) {
            $this->userGroups->removeElement($userGroup);
            $userGroup->removeUser($this);
        }

        return $this;
    }
}
