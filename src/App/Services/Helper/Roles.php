<?php
/**
 * /src/App/Services/Helper/Roles.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Helper;

/**
 * Class Roles
 *
 * @see /app/config/services_helper.yml
 *
 * @category    Service
 * @package     App\Services\Helper
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Roles
{
    const ROLE_USER         = 'ROLE_USER';
    const ROLE_ADMIN        = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN  = 'ROLE_SUPER_ADMIN';

    /**
     * Roles hierarchy.
     *
     * @var array
     */
    private $rolesHierarchy;

    /**
     * RolesHelper constructor.
     *
     * @param   array   $rolesHierarchy This is a 'security.role_hierarchy.roles' parameter value
     *
     * @return  Roles
     */
    public function __construct(array $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return string[]
     */
    public function getRoles()
    {
        $roles = [];

        $iterator = function($value) use (&$roles) {
            $roles[] = $value;
        };

        array_walk_recursive($this->rolesHierarchy, $iterator);

        return array_unique($roles);
    }

    /**
     * Getter method for role label.
     *
     * @param   string  $role
     *
     * @return  string
     */
    public function getRoleLabel($role)
    {
        switch ($role) {
            case self::ROLE_USER:
                $output = 'Normal users';
                break;
            case self::ROLE_ADMIN:
                $output = 'Admin users';
                break;
            case self::ROLE_SUPER_ADMIN:
                $output = 'Root users';
                break;
            default:
                $output = 'Unknown';
                break;
        }

        return $output;
    }
}
