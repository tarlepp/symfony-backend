<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Rest/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Rest;

use App\Entity\Author as AuthorEntity;
use App\Entity\Interfaces\EntityInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Author
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author implements Interfaces\RestDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @JMS\Type("string")
     */
    public $name = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMS\Type("string")
     */
    public $description = '';

    /**
     * Method to load DTO data from author entity.
     *
     * @param   EntityInterface|AuthorEntity  $entity
     *
     * @return  Interfaces\RestDto|Author
     */
    public function load(EntityInterface $entity): Interfaces\RestDto
    {
        $this->id = $entity->getId();
        $this->name = $entity->getName();
        $this->description = $entity->getDescription();

        return $this;
    }

    /**
     * Method to update specified entity with DTO data.
     *
     * @param   EntityInterface|AuthorEntity    $entity
     *
     * @return  EntityInterface|AuthorEntity
     */
    public function update(EntityInterface $entity): EntityInterface
    {
        $entity->setName($this->name);
        $entity->setDescription($this->description);

        return $entity;
    }
}
