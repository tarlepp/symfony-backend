<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/TransUnitTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\TransUnit as TransUnitEntity;
use App\Repository\TransUnit as TransUnitRepository;
use App\Services\Rest\TransUnit as TransUnitService;
use App\Tests\RestServiceTestCase;

/**
 * Class TransUnitTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TransUnitTest extends RestServiceTestCase
{
    /**
     * @var TransUnitService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.trans_unit';

    /**
     * @var string
     */
    protected $entityName = TransUnitEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = TransUnitRepository::class;
}
