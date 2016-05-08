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
        $roles = array();

        $iterator = function($value) use (&$roles) {
            $roles[] = $value;
        };

        array_walk_recursive($this->rolesHierarchy, $iterator);

        return array_unique($roles);
    }
}
