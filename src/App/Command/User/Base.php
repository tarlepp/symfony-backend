<?php

namespace App\Command\User;

use App\Entity\User as Entity;
use App\Services\User as Service;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

abstract class Base extends ContainerAwareCommand
{
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
     * @var Service
     */
    protected $service;

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

        // Store user service object
        $this->service = $this->getContainer()->get('app.services.user');
    }

    /**
     * Helper method to get user object by username or email.
     *
     * @param   bool    $showUserInformation
     *
     * @return  Entity
     */
    protected function getUser($showUserInformation = false)
    {
        $user = null;

        while (null === $user) {
            $question = new Question('Username or email: ', $this->input->getOption('username'));
            $username = $this->io->askQuestion($question);

            try {
                $user = $this->service->getByUsername($username);
            } catch (UsernameNotFoundException $error) {
                $this->io->warning($error->getMessage());
            }
        }

        if ($showUserInformation) {
            $this->printUserInformation($user);
        }

        return $user;
    }

    /**
     * Helper method to encode password for provided user object.
     *
     * @param   Entity  $user
     * @param   string  $password
     *
     * @return  void
     */
    protected function encodePassword(Entity $user, $password)
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
     * @param   Entity  $user
     *
     * @return  void
     */
    protected function printUserInformation(Entity $user)
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
     * @param   Entity  $user
     *
     * @return  Entity
     */
    protected function store(Entity $user)
    {
        // Store user to database
        return $this->service->save($user);
    }
}