<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Helper/Interfaces/Response.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest\Helper\Interfaces;

use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use JMS\Serializer\Context;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Interface Response
 *
 * @package App\Services\Rest\Helper\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Response
{
    /**
     * Constants for response output formats.
     *
     * @var string
     */
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    /**
     * Response constructor.
     *
     * @param   Serializer  $serializer
     */
    public function __construct(Serializer $serializer);

    /**
     * Setter for resource service.
     *
     * @param   ResourceServiceInterface    $resourceService
     *
     * @return  Response
     */
    public function setResourceService(ResourceServiceInterface $resourceService): Response;

    /**
     * Getter for current resource service.
     *
     * @return ResourceServiceInterface
     */
    public function getResourceService(): ResourceServiceInterface;

    /**
     * Helper method to create response for request.
     *
     * @throws  HttpException
     *
     * @param   HttpFoundationRequest   $request
     * @param   mixed                   $data
     * @param   integer                 $httpStatus
     * @param   string                  $format
     * @param   Context                 $context
     *
     * @return  HttpFoundationResponse
     */
    public function createResponse(
        HttpFoundationRequest $request,
        $data,
        int $httpStatus = 200,
        string $format = null,
        Context $context = null
    ): HttpFoundationResponse;

    /**
     * Helper method to get serialization context for query.
     *
     * @param   HttpFoundationRequest $request
     *
     * @return  Context
     */
    public function getSerializeContext(HttpFoundationRequest $request): Context;
}
