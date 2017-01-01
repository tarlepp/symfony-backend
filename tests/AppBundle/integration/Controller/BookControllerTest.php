<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/BookControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\BookController;
use App\Repository\Book as BookRepository;
use App\Services\Rest\Book as BookResourceService;
use App\Tests\RestControllerTestCase;

/**
 * Class BookControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookControllerTest extends RestControllerTestCase
{
    /**
     * @var string
     */
    protected static $controllerName = BookController::class;

    /**
     * @var string
     */
    protected static $resourceServiceName = BookResourceService::class;

    /**
     * @var string
     */
    protected static $repositoryName = BookRepository::class;
}
