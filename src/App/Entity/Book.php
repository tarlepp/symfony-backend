<?php
declare(strict_types=1);
/**
 * /src/App/Entity/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 * Book
 *
 * @ORM\Table(
 *      name="book",
 *      indexes={
 *          @ORM\Index(name="author", columns={"author"}),
 *          @ORM\Index(name="createdBy_id", columns={"createdBy_id"}),
 *          @ORM\Index(name="updatedBy_id", columns={"updatedBy_id"}),
 *          @ORM\Index(name="deletedBy_id", columns={"deletedBy_id"})
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Book"
 *  )
 *
 * @JMS\XmlRoot("book")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Book implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Book.id",
     *      "Author.books",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="id",
     *      type="guid",
     *      nullable=false,
     *  )
     * @ORM\Id()
     */
    private $id;

    /**
     * @var \App\Entity\Author
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Book.author",
     *  })
     * @JMS\Type("App\Entity\Author")
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\Author",
     *      inversedBy="books",
     *  )
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="author",
     *          referencedColumnName="id",
     *      ),
     *  })
     */
    private $author;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Book.title",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="title",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $title;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Book.description",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="description",
     *      type="text",
     *      nullable=false,
     *  )
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Book.releaseDate",
     *  })
     * @JMS\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(
     *      name="releaseDate",
     *      type="date",
     *      nullable=false,
     *  )
     */
    private $releaseDate;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param Author $author
     *
     * @return Book
     */
    public function setAuthor(Author $author) : Book
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return Author
     */
    public function getAuthor() : Author
    {
        return $this->author;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle(string $title) : Book
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Book
     */
    public function setDescription(string $description) : Book
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set release date
     *
     * @param \DateTime $releaseDate
     *
     * @return Book
     */
    public function setReleaseDate(\DateTime $releaseDate) : Book
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get release date
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }
}
