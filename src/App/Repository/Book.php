<?php
/**
 * /src/App/Repository/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

use App\Entity;

/**
 * Doctrine repository class for Book entities.
 *
 * @category    Doctrine
 * @package     App\Repository
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Book extends Base
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected $searchColumns = ['title', 'description'];

    // Implement custom entity query methods here
}
