<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/UserLoginTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\UserLogin as UserLoginEntity;
use App\Repository\UserLogin as UserLoginRepository;
use App\Services\Rest\UserLogin as UserLoginService;
use App\Tests\RestServiceTestCase;

/**
 * Class UserLoginTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserLoginTest extends RestServiceTestCase
{
    /**
     * @var UserLoginService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.user_login';

    /**
     * @var string
     */
    protected $entityName = UserLoginEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = UserLoginRepository::class;

    /**
     * {@inheritdoc}
     */
    protected $entityCount = false;
}
