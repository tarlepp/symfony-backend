<?php
declare(strict_types = 1);
/**
 * /src/App/Command/User/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\User;

use App\DTO\Console\User as UserDto;
use App\DTO\Console\UserGroup as UserGroupDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\User as UserEntity;
use App\Entity\UserGroup as UserGroupEntity;
use App\Services\Rest\User as UserService;
use App\Services\Rest\UserGroup as UserGroupService;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class Base which all 'user' specified commands extends. This class contains commonly used methods that all user
 * specified commands use.
 *
 * @package App\Command\User
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base extends ContainerAwareCommand
{
    /**
     * Name of the console command.
     *
     * @var string
     */
    protected static $commandName;

    /**
     * Description of the console command.
     *
     * @var string
     */
    protected static $commandDescription;

    /**
     * Supported command line parameters. This is an array that contains array configuration of each parameter,
     * following structure is supported.
     *
     *  [
     *      'name'          => '', // The option name
     *      'shortcut'      => '', // The shortcuts, can be null, a string of shortcuts delimited by | or an array of
     *                                shortcuts
     *      'mode'          => '', // The option mode: One of the InputOption::VALUE_* constants
     *      'description'   => '', // A description text
     *      'default'       => '', // The default value (must be null for InputOption::VALUE_NONE)
     *  ]
     *
     * @var array
     */
    protected static $commandParameters = [];

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
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserGroupService
     */
    protected $userGroupService;

    /**
     * {@inheritdoc}
     *
     * @throws  InvalidArgumentException
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
        $iterator = function (array $input) {
            return new InputOption(
                $input['name'],
                $input['shortcut']    ?? null,
                $input['mode']        ?? InputOption::VALUE_OPTIONAL,
                $input['description'] ?? '',
                $input['default']     ?? null
            );
        };

        // Configure command
        $this
            ->setName(static::$commandName)
            ->setDescription(static::$commandDescription)
            ->setDefinition(
                new InputDefinition(array_map($iterator, static::$commandParameters))
            )
        ;
    }

    /**
     * Executes the current command.
     *
     * @throws  \LogicException
     * @throws  ServiceCircularReferenceException
     * @throws  ServiceNotFoundException
     *
     * @param   InputInterface  $input  An InputInterface instance
     * @param   OutputInterface $output An OutputInterface instance
     *
     * @return  null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Store input and output objects
        $this->input = $input;
        $this->output = $output;

        // Create output decorator helpers for the Symfony Style Guide.
        $this->io = new SymfonyStyle($this->input, $this->output);

        // Store user and user group service objects
        $this->userService = $this->getContainer()->get('app.services.rest.user');
        $this->userGroupService = $this->getContainer()->get('app.services.rest.user_group');

        // Set title
        $this->io->title($this->getDescription());

        return null;
    }

    /**
     * Helper method to get user object by username or email.
     *
     * @param   bool $showUserInformation
     *
     * @return  UserEntity
     */
    protected function getUser($showUserInformation = false): UserEntity
    {
        $user = null;

        while (null === $user) {
            try {
                $question = new Question('Username or email: ', $this->input->getOption('username'));
                $username = $this->io->askQuestion($question);

                $user = $this->userService->getRepository()->loadUserByUsername($username);
            } catch (\Exception $error) {
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
     * @param   bool $showInformation
     *
     * @return  UserGroupEntity
     */
    protected function getUserGroup($showInformation = false): UserGroupEntity
    {
        $userGroup = null;

        /**
         * Lambda function to format UserGroup data for io table.
         *
         * @param   UserGroupEntity $userGroup
         *
         * @return  array
         */
        $iterator = function (UserGroupEntity $userGroup) {
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
            try {
                // Print console table
                $this->io->table($headers, array_map($iterator, $this->userGroupService->find()));

                $question = new Question('User group ID: ', $this->input->getOption('id'));
                $userGroupId = $this->io->askQuestion($question);

                $userGroup = $this->userGroupService->findOne($userGroupId, true);
            } catch (\Exception $error) {
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
     * @param   UserEntity  $user
     *
     * @return  UserDto
     */
    protected function getUserDto(UserEntity $user): UserDto
    {
        /**
         * Lambda function to extract user group ID values from UserGroup entity
         *
         * @param   UserGroupEntity $userGroup
         *
         * @return  integer
         */
        $iterator = function (UserGroupEntity $userGroup) {
            return $userGroup->getId();
        };

        // Create DTO for user
        $dto = new UserDto();
        $dto->id = $user->getId();
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
     * @param   UserEntity  $user
     *
     * @return  void
     */
    protected function printUserInformation(UserEntity $user)
    {
        // Attributes to print out
        $attributes = [
            'id',
            'username',
            'firstname',
            'surname',
            'email',
            'userGroups',
        ];

        $this->printGenericInformation($user, $attributes);
    }

    /**
     * Helper method to print user information to console.
     *
     * @param   UserGroupEntity $userGroup
     *
     * @return  void
     */
    protected function printUserGroupInformation(UserGroupEntity $userGroup)
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
     * @param   UserDto $userData
     * @param   UserEntity $user
     * @param   Boolean $skipValidation
     *
     * @return  UserEntity
     */
    protected function storeUser(UserDto $userData, UserEntity $user = null, $skipValidation = false): UserEntity
    {
        // Create new UserGroup entity OR use provided one
        $user = $user ?: new UserEntity();
        $user->setUsername($userData->username);
        $user->setFirstname($userData->firstname);
        $user->setSurname($userData->surname);
        $user->setEmail($userData->email);

        empty($userData->plainPassword) ?: $user->setPlainPassword($userData->plainPassword);

        // Clear current user groups, we just want to create those relations from scratch
        $user->clearUserGroups();

        // Iterate user groups and attach those to current user
        foreach ($userData->userGroups as $groupId) {
            $user->addUserGroup($this->userGroupService->getReference($groupId));
        }

        // Store user to database
        return $this->userService->save($user, $skipValidation);
    }

    /**
     * Helper method to store user group entity. Note that this uses DTO pattern.
     *
     * @param   UserGroupDto    $userGroupData
     * @param   UserGroupEntity $userGroup
     *
     * @return  UserGroupEntity
     */
    protected function storeUserGroup(UserGroupDto $userGroupData, UserGroupEntity $userGroup = null): UserGroupEntity
    {
        // Create new UserGroup entity OR use provided one
        $userGroup = $userGroup ?: new UserGroupEntity();
        $userGroup->setName($userGroupData->name);
        $userGroup->setRole($userGroupData->role);

        // Store user group to database
        return $this->userGroupService->save($userGroup);
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
        $iterator = function ($attribute) use ($entity) {
            $method = sprintf(
                'get%s',
                $attribute
            );

            // Get attribute value
            $value = $entity->$method();

            // And we have many-to-many records so map those to get string presentation of each records
            if ($value instanceof PersistentCollection) {
                $iterator = function (UserGroupEntity $entity) {
                    $data = [
                        $entity->getId(),
                        $entity->getName(),
                        $entity->getRole()
                    ];

                    return implode(' - ', $data);
                };

                //$value = implode(' - ', array_map($iterator, $value->toArray()));
                $value = array_map($iterator, $value->toArray());
            }

            return [
                $attribute,
                is_array($value) ? implode(",\n", $value) : $value,
            ];
        };

        // Specify headers and rows
        $headers = ['Attribute', 'Value'];
        $rows = array_map($iterator, $attributes);

        // Print console table
        $this->io->table($headers, $rows);
    }
}
