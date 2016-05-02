<?php
/**
 * /src/App/Command/UserCreateCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command;

// Application components
use App\Entity\User;

// Symfony components
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
class UserChangePasswordCommand extends ContainerAwareCommand
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
        $io = new SymfonyStyle($input, $output);

        // Set title
        $io->title($this->getDescription());

        $io->warning('BE ABSOLUTELY SURE WHAT YOU\'RE DOING.');

        // Fetch user
        $user = $this->getUser($input, $io);

        // Print user information
        $this->printUserInformation($io, $user);

        // Get new password for user
        $password = $this->askNewPassword($io);

        // Get password encoder and encode given password
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);

        // Set encoded password to user entity
        $user->setPassword($encoded);

        /** @var \App\Services\User $userService */
        $userService = $this->getContainer()->get('app.services.user');

        // Store user to database
        $userService->save($user);

        // Uuh all done!
        $io->success('Password changed successfully!');
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
     * Private helper method to print user information to console.
     *
     * @param   SymfonyStyle    $io
     * @param   User            $user
     *
     * @return  void
     */
    private function printUserInformation(SymfonyStyle $io, User $user)
    {
        // Attributes to print out
        $attributes = [
            'id',
            'username',
            'surname',
            'firstname',
            'email',
            'roles',
        ];

        /**
         * Lambda iterator function to return console table row data for given attribute.
         *
         * @param   string  $attribute
         *
         * @return  array
         */
        $iterator = function($attribute) use ($user) {
            $method = sprintf(
                'get%s',
                $attribute
            );

            $value = call_user_func([$user, $method]);

            return [
                $attribute,
                is_array($value) ? implode(' ', $value) : $value,
            ];
        };

        // Specify headers and rows
        $headers = ['Attribute', 'Value'];
        $rows = array_map($iterator, $attributes);

        // Print console table
        $io->table($headers, $rows);
    }

    /**
     * Helper method to get user object by username or email.
     *
     * @param   InputInterface  $input
     * @param   SymfonyStyle    $io
     *
     * @return  User
     */
    private function getUser(InputInterface $input, SymfonyStyle $io)
    {
        $service = $this->getContainer()->get('app.services.user');

        $user = null;

        while (null === $user) {
            $question = new Question('Username or email: ', $input->getOption('username'));
            $username = $io->askQuestion($question);

            try {
                $user = $service->getByUsername($username);
            } catch (UsernameNotFoundException $error) {
                $io->warning($error->getMessage());
            }
        }

        return $user;
    }

    /**
     * @param   SymfonyStyle    $io
     *
     * @return  string
     */
    private function askNewPassword(SymfonyStyle $io)
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

        return $io->askQuestion($question);
    }
}
