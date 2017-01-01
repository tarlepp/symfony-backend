<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\RequestLog as RequestLogEntity;
use App\Repository\RequestLog as RequestLogRepository;
use App\Services\Rest\RequestLog as RequestLogService;
use App\Tests\RestServiceTestCase;

/**
 * Class RequestLogTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLogTest extends RestServiceTestCase
{
    /**
     * @var RequestLogService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.request_log';

    /**
     * @var string
     */
    protected $entityName = RequestLogEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = RequestLogRepository::class;

    /**
     * {@inheritdoc}
     */
    protected $entityCount = false;
}
