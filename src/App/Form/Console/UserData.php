<?php
/**
 * /src/App/Form/Console/UserData.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Console;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserData
 *
 * @package App\Form\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserData
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $firstname;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 255)
     */
    public $surname;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @var array
     */
    public $userGroups;
}
