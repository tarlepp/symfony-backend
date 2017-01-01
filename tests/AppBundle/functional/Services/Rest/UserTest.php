<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/UserTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\User as UserEntity;
use App\Repository\User as UserRepository;
use App\Services\Rest\User as UserService;
use App\Tests\RestServiceTestCase;

/**
 * Class UserTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserTest extends RestServiceTestCase
{
    /**
     * @var UserService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.user';

    /**
     * @var string
     */
    protected $entityName = UserEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = UserRepository::class;

    /**
     * {@inheritdoc}
     */
    protected $entityCount = 5;
}
