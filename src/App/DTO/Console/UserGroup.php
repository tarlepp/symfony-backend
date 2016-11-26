<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Console/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Console;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserGroup
 *
 * @package App\DTO\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroup
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 4, max = 255)
     */
    public $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 4, max = 255)
     */
    public $role;
}
