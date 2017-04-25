<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Helper/Response.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest\Helper;

use App\Services\Rest\Helper\Interfaces\Response as ResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Response
 *
 * @package App\Services\Rest\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Response implements ResponseInterface
{
    /**
     * Content types for supported response output formats.
     *
     * @var array
     */
    private $contentTypes = [
        self::FORMAT_JSON   => 'application/json',
        self::FORMAT_XML    => 'application/xml'
    ];

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ResourceServiceInterface
     */
    private $resourceService;

    /**
     * Response constructor.
     *
     * @param   Serializer  $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Getter for current resource service.
     *
     * @return ResourceServiceInterface
     */
    public function getResourceService(): ResourceServiceInterface
    {
        return $this->resourceService;
    }

    /**
     * Getter for JMS serializer.
     *
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    /**
     * Helper method to get serialization context for query.
     *
     * @param   HttpFoundationRequest $request
     *
     * @return  Context
     */
    public function getSerializeContext(HttpFoundationRequest $request): Context
    {
        // Specify used populate settings
        $populate = (array)$request->get('populate', []);
        $populateAll = \array_key_exists('populateAll', $request->query->all());
        $populateOnly = \array_key_exists('populateOnly', $request->query->all());

        // Get current entity name
        $entityName = $this->getResourceService()->getEntityName();

        $bits = \explode('\\', $entityName);
        $entityName = \end($bits);

        // Determine used default group
        $defaultGroup = $populateAll ? 'Default' : $entityName;

        // Set all associations to be populated
        if ($populateAll && \count($populate) === 0) {
            $associations = $this->getResourceService()->getAssociations();

            $iterator = function (string $assocName) use ($entityName): string {
                return $entityName . '.' . $assocName;
            };

            $populate = \array_map($iterator, $associations);
        }

        if ($populateOnly) {
            $groups = \count($populate) === 0 ? [$defaultGroup] : $populate;
        } else {
            $groups = \array_merge([$defaultGroup], $populate);
        }

        // Create context and set used groups
        return SerializationContext::create()
            ->setGroups($groups)
            ->setSerializeNull(true);
    }

    /**
     * Setter for resource service.
     *
     * @param   ResourceServiceInterface    $resourceService
     *
     * @return  ResponseInterface
     */
    public function setResourceService(ResourceServiceInterface $resourceService): ResponseInterface
    {
        $this->resourceService = $resourceService;

        return $this;
    }

    /**
     * Helper method to create response for request.
     *
     * @throws  HttpException
     *
     * @param   HttpFoundationRequest   $request
     * @param   mixed                   $data
     * @param   null|integer            $httpStatus
     * @param   null|string             $format
     * @param   null|Context            $context
     *
     * @return  HttpFoundationResponse
     */
    public function createResponse(
        HttpFoundationRequest $request,
        $data,
        int $httpStatus = null,
        string $format = null,
        Context $context = null
    ): HttpFoundationResponse {
        $httpStatus = $httpStatus ?? 200;

        if ($format === null) {
            $format = $request->getContentType() === self::FORMAT_XML ? self::FORMAT_XML : self::FORMAT_JSON;
        }

        if ($context === null) {
            $context = $this->getSerializeContext($request);
        }

        try {
            // Create new response
            $response = new HttpFoundationResponse();
            $response->setContent($this->serializer->serialize($data, $format, $context));
            $response->setStatusCode($httpStatus);
        } catch (\Exception $error) {
            throw new HttpException(
                HttpFoundationResponse::HTTP_BAD_REQUEST,
                $error->getMessage(),
                $error,
                [],
                HttpFoundationResponse::HTTP_BAD_REQUEST
            );
        }

        // Set content type
        $response->headers->set('Content-Type', $this->contentTypes[$format]);

        return $response;
    }
}
