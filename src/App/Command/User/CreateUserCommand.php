<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/CreateUserCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\DTO\Console\User as UserDto;
use App\Form\Console\User as UserForm;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CreateUserCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName = 'user:create';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription = 'Create new user to the database.';

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
     *
     * @throws  \InvalidArgumentException
     * @throws  NoResultException
     * @throws  NonUniqueResultException
     * @throws  LogicException
     * @throws  InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize common console command
        parent::execute($input, $output);

        // Ensure that we have user groups
        if ($this->userGroupService->count() === 0) {
            throw new LogicException(
                'You need to have at least one user group. Use \'user:createGroup\' command to create groups.'
            );
        }

        /** @var UserDto $dto */
        $dto = $this->getHelper('form')->interactUsingForm(UserForm::class, $this->input, $this->output);

        // Store user
        $this->storeUser($dto);

        // Uuh all done!
        $this->io->success('New user created!');
    }
}
