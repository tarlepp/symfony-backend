<?php
/**
 * /tests/AppBundle/Services/Helper/RolesTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Services\Helper;

use App\Services\Helper\Roles;
use App\Tests\ContainerTestCase;

/**
 * Class RolesTest
 *
 * @package AppBundle\Services\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RolesTest extends ContainerTestCase
{
    /**
     * @var \App\Services\Helper\Roles
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = $this->getContainer()->get('app.services.helper.roles');
    }

    public function testThatGetRolesReturnsExpected()
    {
        $this->assertEquals(
            [
                'ROLE_LOGGED',
                'ROLE_USER',
                'ROLE_ADMIN',
                'ROLE_ROOT',
            ],
            $this->service->getRoles(),
            'Returned roles are not expected.'
        );
    }

    /**
     * @dataProvider dataProviderTestThatGetRoleLabelReturnsExpected
     *
     * @param   string  $role
     * @param   string  $expected
     */
    public function testThatGetRoleLabelReturnsExpected($role, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->service->getRoleLabel($role),
            'Role label was not expected one.'
        );
    }

    /**
     * @dataProvider dataProviderTestThatGetShortReturnsExpected
     *
     * @param   string  $input
     * @param   string  $expected
     */
    public function testThatGetShortReturnsExpected($input, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->service->getShort($input),
            'Short role name was not expected'
        );
    }

    public function dataProviderTestThatGetRoleLabelReturnsExpected()
    {
        return [
            [Roles::ROLE_LOGGED, 'Logged in users'],
            [Roles::ROLE_USER, 'Normal users'],
            [Roles::ROLE_ADMIN, 'Admin users'],
            [Roles::ROLE_ROOT, 'Root users'],
            ['Not supported role', 'Unknown - Not supported role'],
        ];
    }

    public function dataProviderTestThatGetShortReturnsExpected()
    {
        return [
            [Roles::ROLE_LOGGED, 'logged'],
            [Roles::ROLE_USER, 'user'],
            [Roles::ROLE_ADMIN, 'admin'],
            [Roles::ROLE_ROOT, 'root'],
            ['SOME_CUSTOM_ROLE', 'custom_role']
        ];
    }
}
