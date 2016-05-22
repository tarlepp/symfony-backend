<?php
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
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Book implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * @var integer
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Books",
     *      "Book.author",
     *      "Author.books",
     *  })
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false,
     *  )
     * @ORM\Id()
     * @ORM\GeneratedValue(
     *      strategy="IDENTITY",
     *  )
     */
    private $id;

    /**
     * @var \App\Entity\Author
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Books",
     *      "Book.author",
     *  })
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
     *      "Books",
     *      "Book.title",
     *  })
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
     *      "Books",
     *      "Book.description",
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
     * @var \DateTime
     *
     * @JMS\Groups({
     *      "Default",
     *      "Book",
     *      "Books",
     *      "Book.releaseDate",
     *  })
     *
     * @ORM\Column(
     *      name="releaseDate",
     *      type="date",
     *      nullable=false,
     *  )
     */
    private $releaseDate;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param integer $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return integer
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Simple method to get 'string' presentation about the current record.
     *
     * @return  string
     */
    public function getRecordTitle()
    {
        $parts = [
            $this->getId(),
            $this->getTitle(),
        ];

        return implode(' - ', $parts);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
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
    public function setDescription($description)
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
    public function setReleaseDate($releaseDate)
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
