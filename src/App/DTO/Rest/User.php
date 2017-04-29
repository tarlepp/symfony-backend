<?php
declare(strict_types=1);
/**
 * /src/App/DTO/Rest/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Rest;

use App\DTO\Rest\Interfaces\RestDto;
use App\Entity\User as UserEntity;
use App\Entity\Interfaces\EntityInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class User
 *
 * @JMS\AccessType("public_method")
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  User    patch(Interfaces\RestDto $dto): Interfaces\RestDto
 */
class User extends Base
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    private $username;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    private $surname;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     */
    private $email;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->visited[] = 'username';

        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->visited[] = 'firstName';

        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     *
     * @return User
     */
    public function setSurname(string $surname): User
    {
        $this->visited[] = 'surname';

        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->visited[] = 'email';

        $this->email = $email;

        return $this;
    }

    /**
     * Method to load DTO data from specified entity.
     *
     * @param   EntityInterface|UserEntity  $entity
     *
     * @return  RestDto
     */
    public function load(EntityInterface $entity): RestDto
    {
        $this->username = $entity->getUsername();
        $this->firstname = $entity->getFirstname();
        $this->surname = $entity->getSurname();
        $this->email = $entity->getEmail();

        return $this;
    }

    /**
     * Method to update specified entity with DTO data.
     *
     * @param   EntityInterface|UserEntity  $entity
     *
     * @return  EntityInterface|UserEntity
     */
    public function update(EntityInterface $entity): EntityInterface
    {
        $entity->setUsername($this->username);
        $entity->setFirstname($this->firstname);
        $entity->setSurname($this->surname);
        $entity->setEmail($this->email);

        return $entity;
    }
}
