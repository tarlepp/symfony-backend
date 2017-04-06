<?php
declare(strict_types = 1);
/**
 * /src/App/Doctrine/Behaviours/Blameable.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Doctrine\Behaviours;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableMethods;

/**
 * Blameable trait.
 *
 * Should be used inside entity where you need to track which user created or updated it.
 *
 * Note that this uses KnpLabs/DoctrineBehaviors (https://github.com/KnpLabs/DoctrineBehaviors) and we just need to
 * override property definitions and add some custom functions to it.
 *
 * @package App\Doctrine\Behaviours
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Blameable
{
    // Traits
    use BlameableMethods;

    /**
     * Created user
     *
     * @var null|\App\Entity\User
     *
     * @JMS\Groups({
     *      "Author.createdBy",
     *      "Book.createdBy",
     *      "Locale.createdBy",
     *      "Translation.createdBy",
     *      "TransUnit.createdBy",
     *      "User.createdBy",
     *      "UserGroup.createdBy",
     *  })
     * @JMS\Type("App\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="created_by_id",
     *          referencedColumnName="id",
     *          nullable=true,
     *          onDelete="SET NULL",
     *      ),
     *  })
     */
    protected $createdBy;

    /**
     * Updated user
     *
     * @var null|\App\Entity\User
     *
     * @JMS\Groups({
     *      "Author.updatedBy",
     *      "Book.updatedBy",
     *      "Locale.updatedBy",
     *      "Translation.updatedBy",
     *      "TransUnit.updatedBy",
     *      "User.updatedBy",
     *      "UserGroup.updatedBy",
     *  })
     * @JMS\Type("App\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="updated_by_id",
     *          referencedColumnName="id",
     *          nullable=true,
     *          onDelete="SET NULL",
     *      ),
     *  })
     */
    protected $updatedBy;

    /**
     * Will be mapped to either string or user entity by BlameableSubscriber
     *
     * Note that this is not used atm.
     *
     * @var null|\App\Entity\User
     *
     * @JMS\Exclude()
     * @JMS\Groups({
     *      "Author.deletedBy",
     *      "Book.deletedBy",
     *      "Locale.deletedBy",
     *      "Translation.deletedBy",
     *      "TransUnit.deletedBy",
     *      "User.deletedBy",
     *      "UserGroup.deletedBy",
     *  })
     * @JMS\Type("App\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="deleted_by_id",
     *          referencedColumnName="id",
     *          nullable=true,
     *          onDelete="SET NULL",
     *      ),
     *  })
     */
    protected $deletedBy;
}
