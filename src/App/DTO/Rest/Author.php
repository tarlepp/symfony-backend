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

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class Author
 *
 * @JMS\AccessType("public_method")
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Author  patch(Interfaces\RestDto $dto): Interfaces\RestDto
 */
class Author extends Base
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @JMS\Type("string")
     */
    private $name = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMS\Type("string")
     */
    private $description = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->visited[] = 'name';

        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Author
     */
    public function setDescription(string $description): Author
    {
        $this->visited[] = 'description';

        $this->description = $description;

        return $this;
    }

    /**
     * Method to load DTO data from author entity.
     *
     * @param   EntityInterface|AuthorEntity  $entity
     *
     * @return  Interfaces\RestDto|Author
     */
    public function load(EntityInterface $entity): Interfaces\RestDto
    {
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
