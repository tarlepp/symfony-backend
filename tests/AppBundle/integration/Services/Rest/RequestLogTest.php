<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Services/Rest/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Services\Rest;

use App\Services\Rest\RequestLog;
use App\Tests\RestServiceTestCase;

/**
 * Class RequestLogTest
 *
 * @package AppBundle\integration\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLogTest extends RestServiceTestCase
{
    /**
     * @var RequestLog
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.request_log';

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\RequestLog';

    /**
     * @var string
     */
    protected $repositoryName = 'App\Repository\RequestLog';

    /**
     * {@inheritdoc}
     */
    protected $entityCount = false;
}
