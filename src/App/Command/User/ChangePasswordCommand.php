<?php
/**
 * /src/App/Command/User/CreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class ChangePasswordCommand
 *
 * @category    Console
 * @package     App\Command\User
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ChangePasswordCommand extends Base
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected $commandName = 'user:changePassword';

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected $commandDescription = 'Change user\'s password.';

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
        $user->setPlainPassword($this->askNewPassword());

        // Store user
        $this->storeUser($user, true);

        // Uuh all done!
        $this->io->success('Password changed successfully!');
    }

    /**
     * Helper method to ask new password.
     *
     * @return  string
     */
    private function askNewPassword()
    {
        // Create new question
        $question = new Question('New password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $question->setValidator(
            function ($value) {
                if (trim($value) == '') {
                    throw new \Exception('The password can not be empty');
                }

                return $value;
            }
        );

        return $this->io->askQuestion($question);
    }
}
