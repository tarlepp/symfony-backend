<?php
declare(strict_types=1);
/**
 * /src/App/Controller/RestController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;

/**
 * Class RestController
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class RestController implements Interfaces\RestController
{
    /**
     * @var ResourceServiceInterface
     */
    protected $resourceService;

    /**
     * @var RestHelperResponseInterface
     */
    protected $restHelperResponse;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ResourceServiceInterface $resourceService,
        RestHelperResponseInterface $restHelperResponse
    ) {
        $this->resourceService = $resourceService;
        $this->restHelperResponse = $restHelperResponse;

        $this->restHelperResponse->setResourceService($this->resourceService);
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceService() : ResourceServiceInterface
    {
        return $this->resourceService;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseService() : RestHelperResponseInterface
    {
        return $this->restHelperResponse;
    }
}
