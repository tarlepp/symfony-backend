<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/EditUserCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\User as UserEntity;
use App\Entity\UserGroup as UserGroupEntity;
use App\DTO\Console\User as UserDto;
use App\Form\Console\User as UserForm;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditUserCommand
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class EditUserCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName = 'user:edit';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription = 'Edit user\'s information.';

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

        $userFound = false;

        // Ask user till user accept founded user
        while (!$userFound) {
            // Fetch user and show user information
            $user = $this->getUser(true);

            $userFound = $this->io->confirm('Is this the user who\'s information you want to change?', false);
        }

        /** @var UserEntity $user */

        // Get DTO for current user
        $dto = $this->getUserDto($user);

        /**
         * Lambda function to get user group id values.
         *
         * @param   UserGroupEntity   $userGroup
         *
         * @return  string
         */
        $iterator = function (UserGroupEntity $userGroup): string {
            return $userGroup->getId();
        };

        // Set user groups
        $dto->userGroups = array_map($iterator, $user->getUserGroups()->toArray());

        /** @var UserDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(
            UserForm::class,
            $this->input,
            $this->output,
            ['data' => $dto]
        );

        // Store user
        $this->storeUser($dto, $user);

        // Uuh all done!
        $this->io->success('User information changed successfully!');
    }
}
