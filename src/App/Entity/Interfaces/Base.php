<?php
/**
 * /src/App/Entity/Interfaces/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity\Interfaces;

/**
 * Interface Base
 *
 * @category    Interface
 * @package     App\Entity\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Base
{
    /**
     * Returns a string representation of entity.
     *
     * @return  string
     */
    public function __toString();
}