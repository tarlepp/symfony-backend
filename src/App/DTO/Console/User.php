<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Console/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Console;

use App\Entity\User as UserEntity;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @AppAssert\UniqueUsername()
 * @AppAssert\UniqueEmail()
 *
 * @package App\DTO\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User implements Interfaces\User
{
    /**
     * @var string
     */
    public $id = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $username = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $firstname = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $surname = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     */
    public $email = '';

    /**
     * @var string
     */
    public $plainPassword = '';

    /**
     * @var array
     */
    public $userGroups = [];

    /**
     * Getter method for user ID value.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Getter method for username value.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Getter method for email value.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Method to load DTO data from user entity.
     *
     * @param   UserEntity  $user
     *
     * @return  User
     */
    public function loadFromEntity(UserEntity $user): User
    {
        $this->id = $user->getId();
        $this->username = $user->getUsername();
        $this->firstname = $user->getFirstname();
        $this->surname = $user->getSurname();
        $this->email = $user->getEmail();
        $this->userGroups = $user->getUserGroups();

        return $this;
    }
}
