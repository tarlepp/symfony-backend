<?php
/**
 * /tests/AppBundle/Services/Rest/UserLoginTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Services\Rest;

use App\Services\Rest\UserLogin;
use App\Tests\RestServiceTestCase;

/**
 * Class UserLoginTest
 *
 * @package AppBundle\Services\Rest
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
    protected $serviceName = 'app.services.rest.userLogin';

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
