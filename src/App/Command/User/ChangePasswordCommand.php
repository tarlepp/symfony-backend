<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/CreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\User;
use App\DTO\Console\User as UserDto;
use App\Form\Console\UserPassword as UserPasswordForm;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ChangePasswordCommand
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ChangePasswordCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName = 'user:changePassword';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription = 'Change user\'s password.';

    /**
     * Supported command line parameters.
     *
     * @var array
     */
    protected static $commandParameters = [
        [
            'name'          => 'username',
            'description'   => 'Username',
        ],
    ];

    /**
     * {@inheritdoc}
     *
     * @throws  InvalidArgumentException
     * @throws  LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize common console command
        parent::execute($input, $output);

        // Display warning
        $this->io->warning('BE ABSOLUTELY SURE WHAT YOU\'RE DOING.');

        $userFound = false;

        // Ask user till user accept founded user
        while (!$userFound) {
            // Fetch user and show user information
            $user = $this->getUser(true);

            $userFound = $this->io->confirm('Is this the user who\'s password you want to change?', false);
        }

        /** @var User $user */

        // Get DTO for current user
        $dto = $this->getUserDto($user);

        /** @var UserDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(
            UserPasswordForm::class,
            $this->input,
            $this->output,
            ['data' => $dto]
        );

        // Store user
        $this->storeUser($dto, $user);

        // Uuh all done!
        $this->io->success('Password changed successfully!');
    }
}
