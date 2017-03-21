<?php
declare(strict_types = 1);
/**
 * /src/App/Entity/Translation.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Translation
 *
 * @ORM\Table(
 *      name="translation",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="uq_trans_unit_locale",
 *              columns={"trans_unit_id", "locale_id"},
 *          ),
 *      },
 *      indexes={
 *          @ORM\Index(name="trans_unit_id", columns={"trans_unit_id"}),
 *          @ORM\Index(name="locale_id", columns={"locale_id"}),
 *          @ORM\Index(name="created_by_id", columns={"created_by_id"}),
 *          @ORM\Index(name="updated_by_id", columns={"updated_by_id"}),
 *          @ORM\Index(name="deleted_by_id", columns={"deleted_by_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\Translation"
 *  )
 *
 * @JMS\XmlRoot("translation")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Translation implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * TransUnit id.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Translation",
     *      "Translation.id",
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
     * @var \App\Entity\TransUnit
     *
     * @JMS\Groups({
     *      "Translation.transUnit",
     *  })
     * @JMS\Type("App\Entity\TransUnit")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\TransUnit",
     *      inversedBy="translations",
     *      cascade={"all"},
     *  )
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="trans_unit_id",
     *          referencedColumnName="id",
     *          onDelete="CASCADE",
     *          nullable=false,
     *      ),
     *  })
     */
    private $transUnit;

    /**
     * Translation locale
     *
     * @var \App\Entity\Locale
     *
     * @JMS\Groups({
     *      "Default",
     *      "Translation",
     *      "Translation.locale",
     *  })
     * @JMS\Type("App\Entity\Locale")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\Locale",
     *      inversedBy="translations",
     *      cascade={"all"},
     *  )
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="locale_id",
     *          referencedColumnName="id",
     *          onDelete="CASCADE",
     *          nullable=false,
     *      ),
     *  })
     */
    private $locale;

    /**
     * Translation content
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "Translation",
     *      "Translation.content",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="content",
     *      type="text",
     *      nullable=false,
     *  )
     */
    private $content;

    /**
     * Translation constructor.
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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return TransUnit
     */
    public function getTransUnit(): TransUnit
    {
        return $this->transUnit;
    }

    /**
     * @param TransUnit $transUnit
     *
     * @return Translation
     */
    public function setTransUnit(TransUnit $transUnit): Translation
    {
        $this->transUnit = $transUnit;

        return $this;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @param Locale $locale
     *
     * @return Translation
     */
    public function setLocale(Locale $locale): Translation
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Translation
     */
    public function setContent(string $content): Translation
    {
        $this->content = $content;

        return $this;
    }
}
