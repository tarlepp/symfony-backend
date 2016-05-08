<?php
/**
 * /src/App/Command/User/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

// Application components
use App\Entity\User as EntityUser;
use App\Entity\UserGroup as EntityUserGroup;
use App\Services\User as ServiceUser;
use App\Services\UserGroup as ServiceUserGroup;

// Symfony components
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class Base
 *
 * @category    Console
 * @package     App\Command\User
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base extends ContainerAwareCommand
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected $commandName;

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected $commandDescription;

    /**
     * Supported command line parameters. This is an array that contains array configuration of each parameter,
     * following structure is supported.
     *
     *  [
     *      'name'          => '', // The option name
     *      'shortcut'      => '', // The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
     *      'mode'          => '', // The option mode: One of the InputOption::VALUE_* constants
     *      'description'   => '', // A description text
     *      'default'       => '', // The default value (must be null for InputOption::VALUE_NONE)
     *  ]
     *
     * @var array
     */
    protected $commandParameters = [];

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var ServiceUser
     */
    protected $serviceUser;

    /**
     * @var ServiceUserGroup
     */
    protected $serviceUserGroup;

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
            return new InputOption(
                $input['name'],
                array_key_exists('shortcut', $input)    ? $input['shortcut']    : null,
                array_key_exists('mode', $input)        ? $input['mode']        : InputOption::VALUE_OPTIONAL,
                array_key_exists('description', $input) ? $input['description'] : '',
                array_key_exists('default', $input)     ? $input['default']     : null
            );
        };

        // Configure command
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setDefinition(
                new InputDefinition(array_map($iterator, $this->commandParameters))
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Store input and output objects
        $this->input = $input;
        $this->output = $output;

        // Create output decorator helpers for the Symfony Style Guide.
        $this->io = new SymfonyStyle($this->input, $this->output);

        // Store user and user group service objects
        $this->serviceUser = $this->getContainer()->get('app.services.user');
        $this->serviceUserGroup = $this->getContainer()->get('app.services.user_group');

        // Set title
        $this->io->title($this->getDescription());
    }

    /**
     * Helper method to get user object by username or email.
     *
     * @param   bool    $showUserInformation
     *
     * @return  EntityUser
     */
    protected function getUser($showUserInformation = false)
    {
        $user = null;

        while (null === $user) {
            $question = new Question('Username or email: ', $this->input->getOption('username'));
            $username = $this->io->askQuestion($question);

            try {
                $user = $this->serviceUser->getByUsername($username);
            } catch (UsernameNotFoundException $error) {
                $this->io->warning($error->getMessage());
            }
        }

        if ($showUserInformation) {
            $this->io->writeln('Following user found');
            $this->printUserInformation($user);
        }

        return $user;
    }

    /**
     * Helper method to encode password for provided user object.
     *
     * @param   EntityUser  $user
     * @param   string      $password
     *
     * @return  void
     */
    protected function encodePassword(EntityUser $user, $password)
    {
        // Get password encoder and encode given password
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);

        // Set encoded password to user entity
        $user->setPassword($encoded);
    }

    /**
     * Private helper method to print user information to console.
     *
     * @param   EntityUser  $user
     *
     * @return  void
     */
    protected function printUserInformation(EntityUser $user)
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
        $this->io->table($headers, $rows);
    }

    /**
     * Helper method to store user entity.
     *
     * @param   EntityUser  $user
     *
     * @return  EntityUser
     */
    protected function storeUser(EntityUser $user)
    {
        // Store user to database
        return $this->serviceUser->save($user);
    }

    /**
     * Helper method to store user group entity.
     *
     * @param   EntityUserGroup $userGroup
     *
     * @return  EntityUserGroup
     */
    protected function storeUserGroup(EntityUserGroup $userGroup)
    {
        // Store user group to database
        return $this->serviceUserGroup->save($userGroup);
    }
}
