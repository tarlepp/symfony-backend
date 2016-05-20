<?php
/**
 * /src/App/Repository/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

use App\Entity;

/**
 * Doctrine repository class for Author entities.
 *
 * @category    Doctrine
 * @package     App\Repository
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author extends Base
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected $searchColumns = ['name', 'description'];

    // Implement custom entity query methods here
}
