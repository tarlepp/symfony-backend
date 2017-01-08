<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/EditUserGroupCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\UserGroup as UserGroupEntity;
use App\DTO\Console\UserGroup as UserGroupDto;
use App\Form\Console\UserGroup as UserGroupForm;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditUserGroupCommand
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class EditUserGroupCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName = 'user:editGroup';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription = 'Edit specified user group data.';

    /**
     * Supported command line parameters.
     *
     * @var array
     */
    protected static $commandParameters = [
        [
            'name'          => 'id',
            'description'   => 'User group ID',
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

        $groupFound = false;

        // Ask user till user accept founded user
        while (!$groupFound) {
            // Fetch user and show user information
            $userGroup = $this->getUserGroup(true);

            $groupFound = $this->io->confirm('Is this the group which information you want to change?', false);
        }

        /** @var UserGroupEntity $userGroup */

        // Create new DTO with selected user group data
        $dto = new UserGroupDto();
        $dto->name = $userGroup->getName();
        $dto->role = $userGroup->getRole();

        /** @var UserGroupDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(
            UserGroupForm::class,
            $this->input,
            $this->output,
            ['data' => $dto]
        );

        // Store user group
        $this->storeUserGroup($dto, $userGroup);

        // Uuh all done!
        $this->io->success('User group information changed successfully!');
    }
}
