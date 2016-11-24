<?php
declare(strict_types=1);
/**
 * /src/App/Controller/Interfaces/RestController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller\Interfaces;

use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;

/**
 * Interface RestController
 *
 * @package App\Controller\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface RestController
{
    /**
     * RestController constructor.
     *
     * @param   ResourceServiceInterface $resourceService
     * @param   RestHelperResponseInterface $restHelperResponse
     */
    public function __construct(
        ResourceServiceInterface $resourceService,
        RestHelperResponseInterface $restHelperResponse
    );

    /**
     * Getter method for resource service.
     *
     * @return ResourceServiceInterface
     */
    public function getResourceService(): ResourceServiceInterface;

    /**
     * Getter method for REST response helper service.
     *
     * @return RestHelperResponseInterface
     */
    public function getResponseService(): RestHelperResponseInterface;
}
