<?php
declare(strict_types = 1);
/**
 * /spec/App/DTO/Console/UserGroupSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\DTO\Console;

use App\DTO\Console\UserGroup;
use PhpSpec\ObjectBehavior;

/**
 * Class UserGroupSpec
 *
 * @mixin UserGroup
 *
 * @package spec\App\DTO\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserGroup::class);
        $this->shouldImplement(UserGroup::class);
    }
}
