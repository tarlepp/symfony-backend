<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Rest/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Rest;

use App\Entity\Book as BookEntity;
use App\Entity\Interfaces\EntityInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Book
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Book implements Interfaces\RestDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \App\Entity\Author
     *
     * @JMS\Type("App\Entity\Author")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $author;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @JMS\Type("string")
     */
    public $title;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    public $description;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'Y-m-d'>")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $releaseDate;

    /**
     * Method to load DTO data from specified entity.
     *
     * @param   EntityInterface|BookEntity  $entity
     *
     * @return  Interfaces\RestDto|Book
     */
    public function load(EntityInterface $entity): Interfaces\RestDto
    {
        $this->id = $entity->getId();
        $this->author = $entity->getAuthor();
        $this->title = $entity->getTitle();
        $this->description = $entity->getDescription();
        $this->releaseDate = $entity->getReleaseDate();

        return $this;
    }

    /**
     * Method to update specified entity with DTO data.
     *
     * @param   EntityInterface|BookEntity  $entity
     *
     * @return  EntityInterface|BookEntity
     */
    public function update(EntityInterface $entity): EntityInterface
    {
        $entity->setAuthor($this->author);
        $entity->setTitle($this->title);
        $entity->setDescription($this->description);
        $entity->setReleaseDate($this->releaseDate);

        return $entity;
    }
}
