<?php
declare(strict_types=1);
/**
 * /src/App/Repository/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

/**
 * Doctrine repository class for Author entities.
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
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
