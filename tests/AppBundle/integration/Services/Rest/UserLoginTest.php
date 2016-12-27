<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Services/Rest/UserLoginTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Services\Rest;

use App\Services\Rest\UserLogin;
use App\Tests\RestServiceTestCase;

/**
 * Class UserLoginTest
 *
 * @package AppBundle\integration\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserLoginTest extends RestServiceTestCase
{
    /**
     * @var UserLogin
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.user_login';

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\UserLogin';

    /**
     * @var string
     */
    protected $repositoryName = 'App\Repository\UserLogin';

    /**
     * {@inheritdoc}
     */
    protected $entityCount = false;
}