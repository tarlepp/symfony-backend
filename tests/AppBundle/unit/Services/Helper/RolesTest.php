<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/Services/Helper/RolesTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\Services\Helper;

use App\Services\Helper\Roles;
use App\Tests\ContainerTestCase;

/**
 * Class RolesTest
 *
 * @package AppBundle\unit\Services\Helper
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
        static::assertEquals(
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
        static::assertEquals(
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
        static::assertEquals(
            $expected,
            $this->service->getShort($input),
            'Short role name was not expected'
        );
    }

    /**
     * @return array
     */
    public function dataProviderTestThatGetRoleLabelReturnsExpected(): array
    {
        return [
            [Roles::ROLE_LOGGED, 'Logged in users'],
            [Roles::ROLE_USER, 'Normal users'],
            [Roles::ROLE_ADMIN, 'Admin users'],
            [Roles::ROLE_ROOT, 'Root users'],
            ['Not supported role', 'Unknown - Not supported role'],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatGetShortReturnsExpected(): array
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
