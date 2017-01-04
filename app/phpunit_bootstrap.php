<?php
declare(strict_types = 1);
/**
 * /app/phpunit_bootstrap.php
 *
 * @package App
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

// Register symfony default autoload
require_once __DIR__ . '/autoload.php';

// Create and boot 'test' kernel
$kernel = new AppKernel('test', true);
$kernel->boot();

// Create new application
$application = new Application($kernel);

////////// Specify functions to initialize test environment - start

// Add the doctrine:database:drop command to the application and run it
$dropDatabaseDoctrineCommand = function () use ($application) {
    $command = new DropDatabaseDoctrineCommand();
    $application->add($command);

    $input = new ArrayInput([
        'command' => 'doctrine:database:drop',
        '--force' => true,
    ]);

    $input->setInteractive(false);

    $command->run($input, new ConsoleOutput());
};

// Add the doctrine:database:create command to the application and run it
$createDatabaseDoctrineCommand = function () use ($application) {
    $command = new CreateDatabaseDoctrineCommand();
    $application->add($command);

    $input = new ArrayInput([
        'command' => 'doctrine:database:create',
    ]);

    $input->setInteractive(false);

    $command->run($input, new ConsoleOutput());
};

// Add the doctrine:schema:update command to the application and run it
$updateSchemaDoctrineCommand = function () use ($application) {
    $command = new UpdateSchemaDoctrineCommand();
    $application->add($command);

    $input = new ArrayInput([
        'command' => 'doctrine:schema:update',
        '--force' => true,
    ]);

    $input->setInteractive(false);

    $command->run($input, new ConsoleOutput());
};

// Add the doctrine:fixtures:load command to the application and run it
$loadFixturesDoctrineCommand = function () use ($application) {
    $command = new \Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand();
    $application->add($command);

    $input = new ArrayInput([
        'command' => 'doctrine:fixtures:load',
        '--no-interaction' => true,
    ]);

    $input->setInteractive(false);

    $command->run($input, new ConsoleOutput());
};

////////// Specify functions to initialize test environment - end

// And finally call each of initialize functions to make test environment ready
array_map(
    'call_user_func',
    [
        $dropDatabaseDoctrineCommand,
        $createDatabaseDoctrineCommand,
        $updateSchemaDoctrineCommand,
        $loadFixturesDoctrineCommand,
    ]
);

// And now the test environment is ready to rock'n'roll!
