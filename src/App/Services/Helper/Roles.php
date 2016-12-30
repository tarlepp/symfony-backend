<?php
declare(strict_types=1);
/**
 * /src/App/Services/Helper/Roles.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Helper;

use App\Services\Helper\Interfaces\Roles as RolesInterface;

/**
 * Class Roles
 *
 * @see /app/config/services_helper.yml
 *
 * @package App\Services\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Roles implements RolesInterface
{
    /**
     * Roles hierarchy.
     *
     * @var array
     */
    private $rolesHierarchy;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        $roles = [
            self::ROLE_LOGGED,
            self::ROLE_USER,
            self::ROLE_ADMIN,
            self::ROLE_ROOT,
        ];

        return $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleLabel(string $role): string
    {
        switch ($role) {
            case self::ROLE_LOGGED:
                $output = 'Logged in users';
                break;
            case self::ROLE_USER:
                $output = 'Normal users';
                break;
            case self::ROLE_ADMIN:
                $output = 'Admin users';
                break;
            case self::ROLE_ROOT:
                $output = 'Root users';
                break;
            default:
                $output = 'Unknown - ' . $role;
                break;
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getShort(string $role): string
    {
        return mb_strtolower(mb_substr($role, mb_strpos($role, '_') + 1));
    }
}
