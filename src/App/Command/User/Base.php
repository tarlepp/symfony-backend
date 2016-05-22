<?php
/**
 * /src/App/Command/User/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\User as EntityUser;
use App\Entity\UserGroup as EntityUserGroup;
use App\Form\Console\UserData;
use App\Form\Console\UserGroupData;
use App\Services\User as ServiceUser;
use App\Services\UserGroup as ServiceUserGroup;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * Helper method to get user group object. This method will print a table which contains all user groups and
     * information about each of those. Then user must "select" one of these user groups by group ID.
     *
     * @param   bool    $showInformation
     *
     * @return  EntityUserGroup
     */
    protected function getUserGroup($showInformation = false)
    {
        $userGroup = null;

        /**
         * Lambda function to format UserGroup data for io table.
         *
         * @param   EntityUserGroup $userGroup
         *
         * @return  array
         */
        $iterator = function(EntityUserGroup $userGroup) {
            return [
                $userGroup->getId(),
                $userGroup->getName(),
                $userGroup->getRole(),
            ];
        };

        // Specify used table header values
        $headers = ['ID', 'Name', 'Role'];

        // And do while user has "selected" one valid user group
        while (null === $userGroup) {
            // Print console table
            $this->io->table($headers, array_map($iterator, $this->serviceUserGroup->find()));

            $question = new Question('User group ID: ', $this->input->getOption('id'));
            $userGroupId = $this->io->askQuestion($question);

            try {
                $userGroup = $this->serviceUserGroup->findOne($userGroupId, true);
            } catch (HttpException $error) {
                $this->io->warning($error->getMessage());
            }
        }

        if ($showInformation) {
            $this->io->writeln('Following user group found');
            $this->printUserGroupInformation($userGroup);
        }

        return $userGroup;
    }

    /**
     * Helper method to get DTO for user entity.
     *
     * @param   EntityUser  $user
     *
     * @return  UserData
     */
    protected function getUserDto(EntityUser $user)
    {
        /**
         * Lambda function to extract user group ID values from UserGroup entity
         *
         * @param   EntityUserGroup $userGroup
         *
         * @return  integer
         */
        $iterator = function(EntityUserGroup $userGroup) {
            return $userGroup->getId();
        };

        // Create DTO for user
        $dto = new UserData();
        $dto->username = $user->getUsername();
        $dto->firstname = $user->getFirstname();
        $dto->surname = $user->getSurname();
        $dto->email = $user->getEmail();
        $dto->userGroups = array_map($iterator, $user->getUserGroups()->toArray());

        return $dto;
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
            'firstname',
            'surname',
            'email',
            'roles',
        ];

        $this->printGenericInformation($user, $attributes);
    }

    /**
     * Helper method to print user information to console.
     *
     * @param   EntityUserGroup $userGroup
     *
     * @return  void
     */
    protected function printUserGroupInformation(EntityUserGroup $userGroup)
    {
        // Attributes to print out
        $attributes = [
            'id',
            'name',
            'role',
        ];

        $this->printGenericInformation($userGroup, $attributes);
    }

    /**
     * Helper method to store user entity.
     *
     * @param   UserData    $userData
     * @param   EntityUser  $user
     * @param   Boolean     $skipValidation
     *
     * @return  EntityUser
     */
    protected function storeUser(UserData $userData, EntityUser $user = null, $skipValidation = false)
    {
        // Create new UserGroup entity OR use provided one
        $user = $user ?: new EntityUser();
        $user->setUsername($userData->username);
        $user->setFirstname($userData->firstname);
        $user->setSurname($userData->surname);
        $user->setEmail($userData->email);
        $user->setPlainPassword($userData->plainPassword);

        // Clear current user groups, we just want to create those relations from scratch
        $user->clearUserGroups();

        // Iterate user groups and attach those to current user
        foreach($userData->userGroups as $groupId) {
            $user->addUserGroup($this->serviceUserGroup->getReference($groupId));
        }

        // Store user to database
        return $this->serviceUser->save($user, $skipValidation);
    }

    /**
     * Helper method to store user group entity. Note that this uses DTO pattern.
     *
     * @param   UserGroupData   $userGroupData
     * @param   EntityUserGroup $userGroup
     *
     * @return  EntityUserGroup
     */
    protected function storeUserGroup(UserGroupData $userGroupData, EntityUserGroup $userGroup = null)
    {
        // Create new UserGroup entity OR use provided one
        $userGroup = $userGroup ?: new EntityUserGroup();
        $userGroup->setName($userGroupData->name);
        $userGroup->setRole($userGroupData->role);

        // Store user group to database
        return $this->serviceUserGroup->save($userGroup);
    }

    /**
     * Helper method to print generic information about given entity and attributes.
     *
     * @param   EntityInterface $entity
     * @param   array           $attributes
     */
    private function printGenericInformation(EntityInterface $entity, array $attributes)
    {
        /**
         * Lambda iterator function to return console table row data for given attribute.
         *
         * @param   string $attribute
         *
         * @return  array
         */
        $iterator = function($attribute) use ($entity) {
            $method = sprintf(
                'get%s',
                $attribute
            );

            $value = call_user_func([$entity, $method]);

            return [
                $attribute,
                is_array($value) ? implode(', ', $value) : $value,
            ];
        };

        // Specify headers and rows
        $headers = ['Attribute', 'Value'];
        $rows = array_map($iterator, $attributes);

        // Print console table
        $this->io->table($headers, $rows);
    }
}
