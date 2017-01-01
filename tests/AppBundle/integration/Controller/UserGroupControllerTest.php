<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/UserGroupControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\UserGroupController;
use App\Repository\UserGroup as UserGroupRepository;
use App\Services\Rest\UserGroup as UserGroupResourceService;
use App\Tests\RestControllerTestCase;

/**
 * Class UserGroupControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupControllerTest extends RestControllerTestCase
{
    /**
     * @var string
     */
    protected static $controllerName = UserGroupController::class;

    /**
     * @var string
     */
    protected static $resourceServiceName = UserGroupResourceService::class;

    /**
     * @var string
     */
    protected static $repositoryName = UserGroupRepository::class;
}
