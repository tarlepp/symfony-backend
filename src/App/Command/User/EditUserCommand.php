<?php
/**
 * /src/App/Command/User/EditUserCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Form\Console\UserData;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditUserCommand
 *
 * @category    Console
 * @package     App\Command\User
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class EditUserCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected $commandName = 'user:edit';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected $commandDescription = 'Edit user\'s information.';

    /**
     * Supported command line parameters.
     *
     * @var array
     */
    protected $commandParameters = [
        [
            'name'          => 'username',
            'description'   => 'Username',
        ],
    ];

    /**
     * {@inheritdoc}
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

        /** @var User $user */

        /**
         * Lambda function to extract user group ID values from UserGroup entity
         *
         * @param   UserGroup   $userGroup
         *
         * @return  integer
         */
        $iterator = function(UserGroup $userGroup) {
            return $userGroup->getId();
        };

        // Create new DTO with selected user data
        $dto = new UserData();
        $dto->username = $user->getUsername();
        $dto->firstname = $user->getFirstname();
        $dto->surname = $user->getSurname();
        $dto->email = $user->getEmail();

        // Note this won't work atm, see https://github.com/matthiasnoback/symfony-console-form/issues/16
        //$dto->userGroups = array_map($iterator, $user->getUserGroups()->toArray());

        /** @var UserData $dto */
        $dto = $this->getHelper('form')->interactUsingForm(
            'App\Form\Console\User',
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
