<?php
declare(strict_types=1);
/**
 * /src/App/DTO/Console/Interfaces/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Console\Interfaces;

/**
 * Interface User
 *
 * @package App\DTO\Console\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface User
{
    /**
     * Getter method for user ID value.
     *
     * @return string|null
     */
    public function getId();

    /**
     * Getter method for username value.
     *
     * @return string|null
     */
    public function getUsername();

    /**
     * Getter method for email value.
     *
     * @return string|null
     */
    public function getEmail();
}
