<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Rest/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Rest;

use App\Entity\Book as BookEntity;
use App\Entity\Author as AuthorEntity;
use App\Entity\Interfaces\EntityInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class Book
 *
 * @JMS\AccessType("public_method")
 *
 * @package App\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Book    patch(Interfaces\RestDto $dto): Interfaces\RestDto
 */
class Book extends Base
{
    /**
     * @var AuthorEntity
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
     * @return AuthorEntity
     */
    public function getAuthor(): AuthorEntity
    {
        return $this->author;
    }

    /**
     * @param AuthorEntity $author
     *
     * @return Book
     */
    public function setAuthor(AuthorEntity $author): Book
    {
        $this->visited[] = 'author';

        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Book
     */
    public function setTitle(string $title): Book
    {
        $this->visited[] = 'title';

        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Book
     */
    public function setDescription($description = null): Book
    {
        $this->visited[] = 'description';

        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     *
     * @return Book
     */
    public function setReleaseDate(\DateTime $releaseDate): Book
    {
        $this->visited[] = 'releaseDate';

        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Method to load DTO data from specified entity.
     *
     * @param   EntityInterface|BookEntity  $entity
     *
     * @return  Interfaces\RestDto|Book
     */
    public function load(EntityInterface $entity): Interfaces\RestDto
    {
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
