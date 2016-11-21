<?php
declare(strict_types=1);
/**
 * /src/App/Services/Helper/Interfaces/SearchTerm.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Helper\Interfaces;

/**
 * Interface SearchTerm
 *
 * @package App\Services\Helper\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface SearchTerm
{
    // Used OPERAND constants
    const OPERAND_OR = 'or';
    const OPERAND_AND = 'and';

    // Used MODE constants
    const MODE_STARTS_WITH = 1;
    const MODE_ENDS_WITH = 2;
    const MODE_FULL = 3;
}
