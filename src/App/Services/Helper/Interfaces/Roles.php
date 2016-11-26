<?php
declare(strict_types=1);
/**
 * /src/App/Services/Helper/Interfaces/Roles.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Helper\Interfaces;

/**
 * Interface Roles
 *
 * @package App\Services\Helper\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Roles
{
    // Used role constants
    const ROLE_LOGGED   = 'ROLE_LOGGED';
    const ROLE_USER     = 'ROLE_USER';
    const ROLE_ADMIN    = 'ROLE_ADMIN';
    const ROLE_ROOT     = 'ROLE_ROOT';

    /**
     * RolesHelper constructor.
     *
     * @param   array   $rolesHierarchy This is a 'security.role_hierarchy.roles' parameter value
     */
    public function __construct(array $rolesHierarchy);

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * Getter method for role label.
     *
     * @param   string  $role
     *
     * @return  string
     */
    public function getRoleLabel(string $role): string;

    /**
     * Getter method for short role.
     *
     * @param   string  $role
     *
     * @return  string
     */
    public function getShort(string $role): string;
}
