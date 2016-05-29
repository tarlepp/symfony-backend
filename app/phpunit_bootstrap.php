<?php
/**
 * /app/phpunit_bootstrap.php
 *
 * @category    Tests
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

// Register symfony default autoload
require_once 'autoload.php';

// Create and boot 'test' kernel
$kernel = new AppKernel('test', true); // create a "test" kernel
$kernel->boot();

// Create new application
$application = new Application($kernel);

////////// Specify functions to initialize test environment - start

// Add the doctrine:database:drop command to the application and run it
$dropDatabaseDoctrineCommand = function() use ($application) {
    $command = new DropDatabaseDoctrineCommand();
    $application->add($command);
    $input = new ArrayInput(array(
        'command' => 'doctrine:database:drop',
        '--force' => true,
    ));
    $command->run($input, new ConsoleOutput());
};

// Add the doctrine:database:create command to the application and run it
$createDatabaseDoctrineCommand = function() use ($application) {
    $command = new CreateDatabaseDoctrineCommand();
    $application->add($command);
    $input = new ArrayInput(
        array(
            'command' => 'doctrine:database:create',
        )
    );
    $command->run($input, new ConsoleOutput());
};

// Add the doctrine:schema:update command to the application and run it
$updateSchemaDoctrineCommand = function() use ($application) {
    $command = new UpdateSchemaDoctrineCommand();
    $application->add($command);
    $input = new ArrayInput(
        array(
            'command' => 'doctrine:schema:update',
            '--force' => true,
        )
    );
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
    ]
);

// And now the test environment is ready to rock'n'roll!
