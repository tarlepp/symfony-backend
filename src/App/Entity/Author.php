<?php
declare(strict_types=1);
/**
 * /src/App/Entity/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author
 *
 * @ORM\Table(
 *      name="author",
 *      indexes={
 *          @ORM\Index(name="created_by_id", columns={"created_by_id"}),
 *          @ORM\Index(name="updated_by_id", columns={"updated_by_id"}),
 *          @ORM\Index(name="deleted_by_id", columns={"deleted_by_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Author",
 *  )
 *
 * @JMS\XmlRoot("author")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Author ID.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Author",
     *      "Author.id",
     *      "Author.books",
     *      "Book.author",
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
     * Author name.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Author",
     *      "Author.name",
     *      "set.DTO",
     *  })
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $name;

    /**
     * Author description.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Author",
     *      "Author.description",
     *      "set.DTO",
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
     * Author books.
     *
     * @var ArrayCollection<Book>
     *
     * @JMS\Groups({
     *      "Author.books",
     *  })
     * @JMS\Type("ArrayCollection<App\Entity\Book>")
     * @JMS\XmlList(entry = "book")
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Book",
     *      mappedBy="author",
     *      cascade={"all"},
     *  )
     */
    private $books;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();

        $this->books = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * Get author books
     *
     * @return ArrayCollection<Book>
     */
    public function getBooks(): ArrayCollection
    {
        return $this->books;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Author
     */
    public function setDescription(string $description): Author
    {
        $this->description = $description;

        return $this;
    }
}
