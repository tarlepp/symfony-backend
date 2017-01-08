<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/CreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\DTO\Console\UserGroup as UserGroupDto;
use App\Form\Console\UserGroup as UserGroupForm;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserGroupCommand
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CreateUserGroupCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName = 'user:createGroup';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription = 'Create new user group to the database.';

    /**
     * Supported command line parameters.
     *
     * @var array
     */
    protected static $commandParameters = [
        [
            'name'          => 'name',
            'description'   => 'Name of the user group',
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

        /** @var UserGroupDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(
            UserGroupForm::class,
            $this->input,
            $this->output
        );

        // Store user group
        $this->storeUserGroup($dto);

        // Uuh all done!
        $this->io->success('New user group created!');
    }
}
