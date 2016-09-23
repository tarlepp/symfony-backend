<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Helper/Response.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest\Helper;

use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use App\Services\Rest\Helper\Interfaces\Response as ResponseInterface;
use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

/**
 * Class Response
 *
 * @package App\Services\Rest\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Response implements ResponseInterface
{
    /**
     * Constants for response output formats.
     *
     * @var string
     */
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    /**
     * Content types for supported response output formats.
     *
     * @var array
     */
    protected $contentTypes = [
        self::FORMAT_JSON   => 'application/json',
        self::FORMAT_XML    => 'application/xml',
    ];

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var ResourceServiceInterface
     */
    protected $resourceService;

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
     * {@inheritdoc}
     */
    public function setResourceService(ResourceServiceInterface $resourceService) : ResponseInterface
    {
        $this->resourceService = $resourceService;

        return $this;
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
    public function createResponse(
        HttpFoundationRequest $request,
        $data,
        $httpStatus = 200,
        $format = 'json'
    ) : HttpFoundationResponse {
        // Create new response
        $response = new HttpFoundationResponse();
        $response->setContent($this->serializer->serialize($data, $format, $this->getSerializeContext($request)));
        $response->setStatusCode($httpStatus);
        $response->headers->set('Content-Type', $this->contentTypes[$format]);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializeContext(HttpFoundationRequest $request) : Context
    {
        // Specify used populate settings
        $populate = (array)$request->get('populate', []);
        $populateAll = array_key_exists('populateAll', $request->query->all());
        $populateOnly = array_key_exists('populateOnly', $request->query->all());

        // Get current entity name
        $entityName = $this->getResourceService()->getEntityName();

        $bits = explode('\\', $entityName);

        // Determine used default group
        $defaultGroup = $populateAll ? 'Default' : end($bits);

        // Set all associations to be populated
        if (count($populate) === 0 && $populateAll) {
            $associations = $this->getResourceService()->getAssociations();

            $populate = array_map('ucfirst', $associations);
        }

        if ($populateOnly) {
            $groups = count($populate) === 0 ? [$defaultGroup] : $populate;
        } else {
            $groups = array_merge([$defaultGroup], $populate);
        }

        // Create context and set used groups
        return SerializationContext::create()
            ->setGroups($groups)
            ->setSerializeNull(true)
        ;
    }
}
