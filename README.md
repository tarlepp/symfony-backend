# What is this?

Simple JSON API which is build on top of [Symfony](https://symfony.com/) framework.

## Main points
* This is just an API, nothing else
* Only JSON responses from API
* JWT authentication
* API documentation

### TODO
- [ ] Configuration for each environment and/or developer
- [ ] Authentication via JWT
- [ ] "Automatic" API doc generation (Swagger)
- [ ] Database connection (Doctrine dbal + orm)
- [ ] Console tools (dbal, migrations, orm)
- [ ] Docker support
- [ ] Logger (monolog) 
- [ ] And _everything_ else...

## Requirements
* PHP 5.6+
* Apache / nginx see configuration information [here](https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html)
 
## Installation
* Use your favorite IDE and get checkout from git OR just use command ```git clone https://github.com/tarlepp/symfony-backend.git```
* Open terminal, go to folder where you make that checkout and run following commands

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

### Environment checks
You need to check that your environment is ready to use this application in CLI and WEB mode.
First step is to make sure that ```var``` directory permissions are set right. Instructions 
for this can be found [here](http://symfony.com/doc/current/book/installation.html#book-installation-permissions).


#### CLI
Open terminal and go to project root directory and run following command.

```bash
$ ./bin/symfony_requirements
```

Check the output from your console.

#### WEB
Open terminal and go to project root directory and run following command to start standalone server.

```bash
$ ./bin/console server:run
```

Open your favorite browser with ```http://127.0.0.1:8000/config.php``` url and check it for any errors.
And if you get just blank page double check your [permissions](http://symfony.com/doc/current/book/installation.html#book-installation-permissions).

### Configuration
*TODO*

### Database initialization
*TODO*

## Development
*TODO*

## Contributing & issues & questions
Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for guidelines.

## Author
[Tarmo Leppänen](https://github.com/tarlepp)

## LICENSE
[The MIT License (MIT)](LICENSE)

Copyright (c) 2016 Tarmo Leppänen