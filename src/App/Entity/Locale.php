<?php
declare(strict_types=1);
/**
 * /src/App/Entity/Locale.php
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
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Locale
 *
 * @AssertCollection\UniqueEntity("code")
 *
 * @ORM\Table(
 *      name="locale",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uq_code", columns={"code"}),
 *      },
 *      indexes={
 *          @ORM\Index(name="created_by_id", columns={"created_by_id"}),
 *          @ORM\Index(name="updated_by_id", columns={"updated_by_id"}),
 *          @ORM\Index(name="deleted_by_id", columns={"deleted_by_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Locale"
 *  )
 *
 * @JMS\XmlRoot("locale")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Locale implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Locale id.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Locale",
     *      "Locale.id",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="id",
     *      type="guid",
     *      nullable=false
     *  )
     * @ORM\Id()
     */
    private $id;

    /**
     * Locale name.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Locale",
     *      "Locale.name",
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
     *      nullable=false
     *  )
     */
    private $name;

    /**
     * Locale short name.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Locale",
     *      "Locale.nameShort",
     *  })
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 10)
     *
     * @ORM\Column(
     *      name="name_short",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $nameShort;

    /**
     * Locale code.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Locale",
     *      "Locale.code",
     *  })
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 10)
     *
     * @ORM\Column(
     *      name="code",
     *      type="string",
     *      length=10,
     *      nullable=false
     *  )
     */
    private $code;

    /**
     * Locale translations.
     *
     * @var ArrayCollection<Translation>
     *
     * @JMS\Groups({
     *      "Locale.translations",
     *  })
     * @JMS\Type("ArrayCollection<App\Entity\Translation>")
     * @JMS\XmlList(entry = "translation")
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Translation",
     *      mappedBy="locale",
     *      cascade={"all"},
     *  )
     */
    private $translations;

    /**
     * TransUnit constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();

        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

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
     * @return Locale
     */
    public function setName(string $name): Locale
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameShort(): string
    {
        return $this->nameShort;
    }

    /**
     * @param string $nameShort
     *
     * @return Locale
     */
    public function setNameShort(string $nameShort): Locale
    {
        $this->nameShort = $nameShort;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Locale
     */
    public function setCode(string $code): Locale
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return ArrayCollection<Translation>
     */
    public function getTranslations(): ArrayCollection
    {
        return $this->translations;
    }
}
