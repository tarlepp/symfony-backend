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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 * Author
 *
 * @ORM\Table(
 *      name="author",
 *      indexes={
 *          @ORM\Index(name="createdBy_id", columns={"createdBy_id"}),
 *          @ORM\Index(name="updatedBy_id", columns={"updatedBy_id"}),
 *          @ORM\Index(name="deletedBy_id", columns={"deletedBy_id"})
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Author"
 *  )
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
     *  })
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
     *  })
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
     * @var \App\Entity\Book[]
     *
     * @JMS\Groups({
     *      "Books",
     *      "Author.books",
     *  })
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Book",
     *      mappedBy="author",
     *  )
     */
    private $books;

    /**
     * User constructor.
     *
     * return User
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
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
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
     * @return Collection|Book[]
     */
    public function getBooks() : Collection
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
    public function setName(string $name) : Author
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
    public function setDescription(string $description) : Author
    {
        $this->description = $description;

        return $this;
    }
}
