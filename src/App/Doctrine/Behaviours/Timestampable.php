<?php
declare(strict_types = 1);
/**
 * /src/App/Doctrine/Behaviours/Timestampable.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Doctrine\Behaviours;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethods;

/**
 * Trait Timestampable
 *
 * Should be used inside entity, that needs to be timestamped.
 *
 * Note that this uses KnpLabs/DoctrineBehaviors (https://github.com/KnpLabs/DoctrineBehaviors) and we just need to
 * override property definitions and add some custom functions to it.
 *
 * @package App\Doctrine\Behaviours
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Timestampable
{
    // Traits
    use TimestampableMethods;

    /**
     * Created at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Groups({
     *      "Author.createdAt",
     *      "Book.createdAt",
     *      "Locale.createdAt",
     *      "Translation.createdAt",
     *      "TransUnit.createdAt",
     *      "User.createdAt",
     *      "UserGroup.createdAt",
     *  })
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(
     *      name="created_at",
     *      type="datetime",
     *      nullable=true,
     *  )
     */
    protected $createdAt;

    /**
     * Updated at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Groups({
     *      "Author.updatedAt",
     *      "Book.updatedAt",
     *      "Locale.updatedAt",
     *      "Translation.updatedAt",
     *      "TransUnit.updatedAt",
     *      "User.updatedAt",
     *      "UserGroup.updatedAt",
     *  })
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(
     *      name="updated_at",
     *      type="datetime",
     *      nullable=true,
     *  )
     */
    protected $updatedAt;

    /**
     * Getter method for 'createdAt' attribute for JSON output.
     *
     * @return string
     */
    public function getCreatedAtJson(): string
    {
        return $this->formatDatetime($this->getCreatedAt());
    }

    /**
     * Getter method for 'updatedAt' attribute for JSON output.
     *
     * @return string
     */
    public function getUpdatedAtJson(): string
    {
        return $this->formatDatetime($this->getUpdatedAt());
    }

    /**
     * Helper method to format given \DateTime object to RFC3339 format.
     *
     * @see https://www.ietf.org/rfc/rfc3339.txt
     *
     * @param   \DateTime|null  $dateTime
     *
     * @return  null|string
     */
    protected function formatDatetime(\DateTime $dateTime = null)
    {
        return $dateTime === null ? null : $dateTime->format(\DATE_RFC3339);
    }
}
