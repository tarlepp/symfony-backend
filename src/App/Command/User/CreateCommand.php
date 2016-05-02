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
class CreateCommand extends Base
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
        // Initialize common console command
        parent::execute($input, $output);

        // Set title
        $this->io->title($this->getDescription());

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
