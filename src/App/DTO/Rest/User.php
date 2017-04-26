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

/**
 * Class User
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User implements RestDto
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $username;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $firstname;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $surname;

    /**
     * @var string
     *
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     */
    public $email;

    /**
     * Method to load DTO data from specified entity.
     *
     * @param   EntityInterface|UserEntity  $entity
     *
     * @return  RestDto
     */
    public function load(EntityInterface $entity): RestDto
    {
        $this->id = $entity->getId();
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