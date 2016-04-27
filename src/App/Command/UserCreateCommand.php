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

// 3rd party components
use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UserCreateCommand
 *
 * @category    Console
 * @package     App\Command
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserCreateCommand extends ContainerAwareCommand
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
            ->setName('user:create')
            ->setDescription('Create new user to the database.')
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

        /** @var FormHelper $formHelper */
        $formHelper = $this->getHelper('form');

        /** @var User $user */
        $user = $formHelper->interactUsingForm('App\Form\Console\User', $input, $output);

        // Get password encoder and encode given password
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $user->getPassword());

        // Set encoded password to user entity
        $user->setPassword($encoded);

        /** @var \App\Services\User $userService */
        $userService = $this->getContainer()->get('app.services.user');

        // Store user to database
        $userService->save($user);

        // Uuh all done!
        $io->success('New user created!');
    }

    /**
     * Helper method to return command input parameters.
     *
     * @return array
     */
    private function getInputParameters()
    {
        return [
            [
                'attribute'     => 'username',
                'description'   => 'Username',
            ],
            [
                'attribute'     => 'firstname',
                'description'   => 'Firstname',
            ],
            [
                'attribute'     => 'surname',
                'description'   => 'Surname',
            ],
            [
                'attribute'     => 'email',
                'description'   => 'Email address',
            ],
        ];
    }
}
