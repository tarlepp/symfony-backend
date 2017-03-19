<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/TranslationControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\TranslationController;
use App\Repository\Translation as TranslationRepository;
use App\Services\Rest\TransUnit as TranslationResourceService;
use App\Tests\RestControllerTestCase;

/**
 * Class TranslationControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TranslationControllerTest extends RestControllerTestCase
{
    /**
     * @var string
     */
    protected static $controllerName = TranslationController::class;

    /**
     * @var string
     */
    protected static $resourceServiceName = TranslationResourceService::class;

    /**
     * @var string
     */
    protected static $repositoryName = TranslationRepository::class;
}
