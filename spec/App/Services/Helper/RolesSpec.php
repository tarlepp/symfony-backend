<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/Helper/RolesSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services\Helper;

use App\Services\Helper\Interfaces\Roles as RolesInterface;
use App\Services\Helper\Roles;
use PhpSpec\ObjectBehavior;

/**
 * Class RolesSpec
 *
 * @package spec\App\Services\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RolesSpec extends ObjectBehavior
{
    function let()
    {
        $rolesHierarchy = [
            'ROLE_USER'     => ['ROLE_LOGGED'],
            'ROLE_ADMIN'    => ['ROLE_USER'],
            'ROLE_ROOT'     => ['ROLE_ADMIN'],
        ];

        $this->beConstructedWith($rolesHierarchy);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Roles::class);
        $this->shouldImplement(RolesInterface::class);
    }

    function it_should_return_expected_value_when_calling_getRoles_method()
    {
        $expected = ['ROLE_LOGGED', 'ROLE_USER', 'ROLE_ADMIN', 'ROLE_ROOT'];

        $this->getRoles()->shouldBeArray();
        $this->getRoles()->shouldReturn($expected);
    }

    function it_should_return_expected_value_when_calling_getRoleLabel_method_with_valid_input()
    {
        $this->getRoleLabel('ROLE_USER')->shouldBeString();
        $this->getRoleLabel('ROLE_USER')->shouldReturn('Normal users');
    }

    function it_should_return_expected_value_when_calling_getRoleLabel_method_with_invalid_input()
    {
        $this->getRoleLabel('foobar')->shouldBeString();
        $this->getRoleLabel('foobar')->shouldReturn('Unknown - foobar');
    }

    function it_should_return_expected_value_when_calling_getShort_method()
    {
        $this->getShort('ROLE_ADMIN')->shouldBeString();
        $this->getShort('ROLE_ADMIN')->shouldReturn('admin');
    }
}
