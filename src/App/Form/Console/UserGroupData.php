<?php
/**
 * /src/App/Form/Console/UserGroupData.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Console;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserGroupData
 *
 * @package App\Form\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupData
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
