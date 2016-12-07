<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/Helper/SearchTermSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services\Helper;

use App\Services\Helper\Interfaces\SearchTerm as SearchTermInterface;
use App\Services\Helper\SearchTerm;
use PhpSpec\ObjectBehavior;

/**
 * Class SearchTermSpec
 *
 * @package spec\App\Services\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SearchTermSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SearchTerm::class);
        $this->shouldImplement(SearchTermInterface::class);
    }
}
