<?php
declare(strict_types = 1);
/**
 * /spec/App/DTO/Console/UserSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\DTO\Console;

use App\DTO\Console\Interfaces\User as UserInterface;
use App\DTO\Console\User;
use PhpSpec\ObjectBehavior;

/**
 * Class UserSpec
 *
 * @mixin User
 *
 * @package spec\App\DTO\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
        $this->shouldImplement(UserInterface::class);
    }
}
