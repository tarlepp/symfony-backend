<?php
declare(strict_types = 1);
/**
 * /src/App/Entity/TransUnit.php
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
 * Class TransUnit
 *
 * @ORM\Table(
 *      name="trans_unit",
 *      indexes={
 *          @ORM\Index(name="created_by_id", columns={"created_by_id"}),
 *          @ORM\Index(name="updated_by_id", columns={"updated_by_id"}),
 *          @ORM\Index(name="deleted_by_id", columns={"deleted_by_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\TransUnit"
 *  )
 *
 * @JMS\XmlRoot("transUnit")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TransUnit implements EntityInterface
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
     *      "TransUnit",
     *      "TransUnit.id",
     *      "Translation.transUnit",
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
     * TransUnit domain.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "TransUnit",
     *      "TransUnit.domain",
     *  })
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="domain",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $domain;

    /**
     * TransUnit key.
     *
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "TransUnit",
     *      "TransUnit.key",
     *  })
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     *
     * @ORM\Column(
     *      name="`key`",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $key;

    /**
     * TransUnit translations.
     *
     * @var ArrayCollection<Translation>
     *
     * @JMS\Groups({
     *      "TransUnit.translations",
     *  })
     * @JMS\Type("ArrayCollection<App\Entity\Translation>")
     * @JMS\XmlList(entry = "translation")
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Translation",
     *      mappedBy="transUnit",
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

        $this->domain = 'messages';
        $this->translations = new ArrayCollection();
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
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     *
     * @return TransUnit
     */
    public function setDomain(string $domain): TransUnit
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return TransUnit
     */
    public function setKey(string $key): TransUnit
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations(): ArrayCollection
    {
        return $this->translations;
    }
}
