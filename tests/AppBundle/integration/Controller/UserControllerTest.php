<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/UserControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\UserController;
use App\Repository\User as UserRepository;
use App\Services\Rest\User as UserResourceService;
use App\Tests\RestControllerTestCase;

/**
 * Class UserControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserControllerTest extends RestControllerTestCase
{
    /**
     * @var string
     */
    protected static $controllerName = UserController::class;

    /**
     * @var string
     */
    protected static $resourceServiceName = UserResourceService::class;

    /**
     * @var string
     */
    protected static $repositoryName = UserRepository::class;
}
