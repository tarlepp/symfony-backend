<?php
/**
 * /src/App/Repository/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

// Application entities
use App\Entity;

// Doctrine components
use Doctrine\ORM\EntityRepository;

/**
 * Base doctrine repository class for entities.
 *
 * @category    Doctrine
 * @package     App\Repository
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base extends EntityRepository
{
    // Implement custom entity query methods here
}
