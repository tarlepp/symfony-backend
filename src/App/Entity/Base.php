<?php
/**
 * /src/App/Entity/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

/**
 * Class Base
 *
 * Abstract base class to all application entity classes.
 *
 * @method  getId() Getter method for entity ID.
 *
 * @category    Model
 * @package     App\Entity
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base
{
    /**
     * Returns a string representation of entity.
     *
     * @return  string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
}
