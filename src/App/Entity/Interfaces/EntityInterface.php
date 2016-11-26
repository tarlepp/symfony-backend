<?php
declare(strict_types=1);
/**
 * /src/App/Entity/Interfaces/EntityInterface.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity\Interfaces;

/**
 * Interface Entity
 *
 * @package App\Entity\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface EntityInterface
{
    /**
     * Get id
     *
     * @return string
     */
    public function getId(): string;
}
