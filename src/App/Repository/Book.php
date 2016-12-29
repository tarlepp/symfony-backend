<?php
declare(strict_types=1);
/**
 * /src/App/Repository/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

/**
 * Doctrine repository class for Book entities.
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Book extends Base
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static $searchColumns = ['title', 'description'];

    // Implement custom entity query methods here
}
