<?php
/**
 * /src/App/Command/UserCreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

// Application components
use App\Entity\User;

// Symfony components
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;

// 3rd party components
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserChangePasswordCommand
 *
 * @category    Console
 * @package     App\Command
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ChangePasswordCommand extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        /**
         * Lambda iterator function to parse specified inputs.
         *
         * @param   array   $input
         *
         * @return  InputOption
         */
        $iterator = function(array $input) {
            return new InputOption($input['attribute'], null, InputOption::VALUE_OPTIONAL, $input['description']);
        };

        // Configure command
        $this
            ->setName('user:changePassword')
            ->setDescription('Change user\'s password')
            ->setDefinition(
                new InputDefinition(array_map($iterator, $this->getInputParameters()))
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize common console command
        parent::execute($input, $output);

        // Set title
        $this->io->title($this->getDescription());
        $this->io->warning('BE ABSOLUTELY SURE WHAT YOU\'RE DOING.');

        // Fetch user and show user information
        $user = $this->getUser(true);

        // Get and set (encode) new password for user
        $this->encodePassword($user, $this->askNewPassword());

        // Store user
        $this->store($user);

        // Uuh all done!
        $this->io->success('Password changed successfully!');
    }

    private function getInputParameters()
    {
        return [
            [
                'attribute'     => 'username',
                'description'   => 'Username',
            ],
        ];
    }

    /**
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
