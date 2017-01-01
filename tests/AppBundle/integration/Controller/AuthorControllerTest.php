<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/AuthorControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\AuthorController;
use App\Repository\Author as AuthorRepository;
use App\Services\Rest\Author as AuthorResourceService;
use App\Tests\RestControllerTestCase;

/**
 * Class AuthorControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorControllerTest extends RestControllerTestCase
{
    /**
     * @var string
     */
    protected static $controllerName = AuthorController::class;

    /**
     * @var string
     */
    protected static $resourceServiceName = AuthorResourceService::class;

    /**
     * @var string
     */
    protected static $repositoryName = AuthorRepository::class;
}
