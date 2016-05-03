<?php
/**
 * /src/App/Command/User/CreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

// Application components
use App\Entity\User;

// Symfony components
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// 3rd party components
use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;

/**
 * Class CreateCommand
 *
 * @category    Console
 * @package     App\Command\User
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CreateCommand extends Base
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
            'name'          => 'password',
            'description'   => 'Password',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize common console command
        parent::execute($input, $output);

        /** @var FormHelper $formHelper */
        $formHelper = $this->getHelper('form');

        /** @var User $user */
        $user = $formHelper->interactUsingForm('App\Form\Console\User', $this->input, $this->output);

        // Get and set (encode) new password for user
        $this->encodePassword($user, $user->getPassword());

        // Store user
        $this->store($user);

        // Uuh all done!
        $this->io->success('New user created!');
    }
}
