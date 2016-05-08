<?php
/**
 * /src/App/Services/RolesHelper.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

/**
 * Class RolesHelper
 *
 * @see /app/config/services.yml
 *
 * @category    Service
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RolesHelper
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
     * @return  RolesHelper
     */
    public function __construct(array $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return array
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
