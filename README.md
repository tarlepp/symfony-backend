# What is this?
[![Build Status](https://travis-ci.org/tarlepp/symfony-backend.png?branch=master)](https://travis-ci.org/tarlepp/symfony-backend)
[![Coverage Status](https://coveralls.io/repos/github/tarlepp/symfony-backend/badge.svg?branch=master)](https://coveralls.io/github/tarlepp/symfony-backend?branch=master)

Simple JSON API which is build on top of [Symfony](https://symfony.com/) framework.

# Table of contents
  * [Main points](#main-points)
    * [TODO](#todo)
  * [Requirements](#requirements)
  * [Installation](#installation)
    * [Environment checks](#environment-checks)
    * [CLI](#cli)
    * [WEB](#web)
    * [Configuration](#configuration)
    * [Database initialization](#database-initialization)
  * [Development](#development)
    * [PHP Code Sniffer](#php-code-sniffer)
    * [Database changes](#database-changes)
    * [Tests](#tests)
  * [Useful resources   tips](#useful-resources--tips)
  * [Contributing &amp; issues &amp; questions](#contributing--issues--questions)
  * [Author](#author)
  * [LICENSE](#license)


# Main points
* This is just an API, nothing else
* Only JSON responses from API
* JWT authentication
* API documentation

## TODO
- [x] Configuration for each environment and/or developer
- [x] Authentication via JWT
- [x] CORS support
- [ ] "Automatic" API doc generation (Swagger)
- [x] Database connection (Doctrine dbal + orm)
- [x] Console tools (dbal, migrations, orm)
- [x] Docker support
- [x] Logger (monolog) 
- [x] TravisCI tests
- [ ] Make tests, every endpoint
- [ ] Docs - Generic 
- [ ] Docs - New api endpoint 
- [ ] Docs - New REST service
- [ ] And _everything_ else...

# Requirements
* PHP 7.0+
* Apache / nginx see configuration information [here](https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html)
 
# Installation
* Use your favorite IDE and get checkout from git OR just use command ```git clone https://github.com/tarlepp/symfony-backend.git```
* Open terminal, go to folder where you make that checkout and run following commands

JWT SSH keys generation
```bash
$ openssl genrsa -out app/var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem
```

Fetch all dependencies
```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Note that this will also ask you some environment settings; db, mail, secrets, jwt, etc.

## Environment checks
You need to check that your environment is ready to use this application in CLI and WEB mode.
First step is to make sure that ```var``` directory permissions are set right. Instructions 
for this can be found [here](http://symfony.com/doc/current/book/installation.html#book-installation-permissions).

## CLI
Open terminal and go to project root directory and run following command.

```bash
$ ./bin/symfony_requirements
```

Check the output from your console.

## WEB
Open terminal and go to project root directory and run following command to start standalone server.

```bash
$ ./bin/console server:run
```

Open your favorite browser with ```http://127.0.0.1:8000/config.php``` url and check it for any errors.
And if you get just blank page double check your [permissions](http://symfony.com/doc/current/book/installation.html#book-installation-permissions).

## Configuration
Application will ask your configuration settings when you first time run ```php composer.phar install``` command.
All those parameters that you should change are in ```/app/config/parameters.yml``` file, so just open that and 
made necessary changes to it.

If you want to answer those parameter values again, you can just delete ```/app/config/parameters.yml``` file and
then run ```php composer.phar update``` command. 

## Database initialization
At start you have just empty database which you have configured in previous topic. To initialize your database
just run following command:

```bash
$ ./bin/console doctrine:schema:update
```

# Development
* [Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html) 

## PHP Code Sniffer
It's highly recommended that you use this tool while doing actual development to application. PHP Code Sniffer is added to project ```dev``` dependencies, so all you need to do is just configure it to your favorite IDE. So the ```phpcs``` command is available via following example command.

```bash
$ ./vendor/bin/phpcs -i
```

If you're using [PhpStorm](https://www.jetbrains.com/phpstorm/) following links will help you to get things rolling.

* [Using PHP Code Sniffer Tool](https://www.jetbrains.com/help/phpstorm/10.0/using-php-code-sniffer-tool.html)
* [PHP Code Sniffer in PhpStorm](https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm)

## Database changes
Generally you will need to generate migration files from each database change that you're doing. Easiest way to
handle these are just following workflow:

1. Made your changes to Entity (```/src/App/Entity/```)
2. Run diff command to create new migration file; 
```bash
$ ./bin/console doctrine:migrations:diff
```

With this you won't need to write those migration files by yourself, just let doctrine handle those - although remember to really look what those generated migration files really contains...

## Tests
Project contains bunch of tests (unit, functional, e2e, etc.) which you can run simply by following command:

```bash
phpunit
```

# Useful resources + tips
* [Symfony Development using PhpStorm](http://blog.jetbrains.com/phpstorm/2014/08/symfony-development-using-phpstorm/) - Guide to configure your PhpStorm for Symfony development
* [PHP Annotations plugin for PhpStorm](https://plugins.jetbrains.com/plugin/7320) - PhpStorm plugin to make annotations really work
* Use 1.1-dev version of composer, so that you can use ```php composer.phar outdated``` command to check package versions

# Contributing & issues & questions
Please see the [CONTRIBUTING.md](.github/CONTRIBUTING.md) file for guidelines.

# Authors
[Tarmo Leppänen](https://github.com/tarlepp)

# LICENSE
[The MIT License (MIT)](LICENSE)

Copyright (c) 2016 Tarmo Leppänen