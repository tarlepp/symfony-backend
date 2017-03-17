<?php
declare(strict_types=1);
/**
 * /src/App/Repository/TransUnit.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

/**
 * Class TransUnit
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TransUnit extends Base
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static $searchColumns = ['domain', 'key'];

    // Implement custom entity query methods here
}
