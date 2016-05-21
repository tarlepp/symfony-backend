<?php
/**
 * /src/App/Command/User/CreateUserCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Form\Console\UserData;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 *
 * @category    Console
 * @package     App\Command\User
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CreateUserCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected $commandName = 'user:create';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected $commandDescription = 'Create new user to the database.';

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
        [
            'name'          => 'firstname',
            'description'   => 'Firstname',
        ],
        [
            'name'          => 'surname',
            'description'   => 'Surname',
        ],
        [
            'name'          => 'email',
            'description'   => 'Email address',
        ],
        [
            'name'          => 'plainPassword',
            'description'   => 'Plain password',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize common console command
        parent::execute($input, $output);

        // Ensure that we have user groups
        if (count($this->serviceUserGroup->find()) === 0) {
            throw new LogicException(
                'You need to have at least one user group. Use \'user:createGroup\' command to create groups.'
            );
        }

        /** @var UserData $dto */
        $dto = $this->getHelper('form')->interactUsingForm('App\Form\Console\User', $this->input, $this->output);

        // Store user
        $this->storeUser($dto);

        // Uuh all done!
        $this->io->success('New user created!');
    }
}
