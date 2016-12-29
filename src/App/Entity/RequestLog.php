<?php
declare(strict_types=1);
/**
 * /src/App/Entity/RequestLog.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Utils\JSON;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RequestLog
 *
 * @ORM\Table(
 *      name="request_log",
 *      indexes={
 *          @ORM\Index(name="user_id", columns={"user_id"}),
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\RequestLog"
 *  )
 *
 * @JMS\XmlRoot("requestLog")
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLog implements EntityInterface
{
    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.id",
     *      "User.requestLog",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="id",
     *      type="guid",
     *      nullable=false,
     *  )
     * @ORM\Id()
     */
    private $id;

    /**
     * @var \App\Entity\User
     *
     * @JMS\Groups({
     *      "RequestLog.user",
     *  })
     * @JMS\Type("App\Entity\User")
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\User",
     *      inversedBy="userRequests",
     *      cascade={"persist"},
     *  )
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="user_id",
     *          referencedColumnName="id",
     *      ),
     *  })
     */
    private $user;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.clientIp",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="client_ip",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $clientIp;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.method",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="method",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $method;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.scheme",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="scheme",
     *      type="string",
     *      length=5,
     *      nullable=false,
     *  )
     */
    private $scheme;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.httpHost",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="http_host",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $httpHost;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.basePath",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="base_path",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $basePath;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.script",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="script",
     *      type="string",
     *      length=255,
     *      nullable=false,
     *  )
     */
    private $script;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.path",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="path",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $path;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.queryString",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="query_string",
     *      type="text",
     *      nullable=true,
     *  )
     */
    private $queryString;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.uri",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="uri",
     *      type="text",
     *      nullable=false,
     *  )
     */
    private $uri;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.controller",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="controller",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $controller;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.action",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="action",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $action;

    /**
     * @var array
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.headers",
     *  })
     * @JMS\Type("array")
     *
     * @ORM\Column(
     *      name="headers",
     *      type="array",
     *  )
     */
    private $headers;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.contentType",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="content_type",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $contentType;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.contentTypeShort",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="content_type_short",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *  )
     */
    private $contentTypeShort;

    /**
     * @var string
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.content",
     *  })
     * @JMS\Type("string")
     *
     * @ORM\Column(
     *      name="content",
     *      type="text",
     *      nullable=true,
     *  )
     */
    private $content;

    /**
     * @var array
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.parameters",
     *  })
     * @JMS\Type("array")
     *
     * @ORM\Column(
     *      name="parameters",
     *      type="array",
     *  )
     */
    private $parameters;

    /**
     * @var integer
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.statusCode",
     *  })
     * @JMS\Type("integer")
     *
     * @ORM\Column(
     *      name="status_code",
     *      type="integer",
     *      nullable=false,
     *  )
     */
    private $statusCode;

    /**
     * @var integer
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.responseContentLength",
     *  })
     * @JMS\Type("integer")
     *
     * @ORM\Column(
     *      name="response_content_length",
     *      type="integer",
     *      nullable=false,
     *  )
     */
    private $responseContentLength;

    /**
     * @var bool
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.isMasterRequest",
     *  })
     * @JMS\Type("boolean")
     *
     * @ORM\Column(
     *      name="is_master_request",
     *      type="boolean",
     *      nullable=false,
     *  )
     */
    private $masterRequest;

    /**
     * @var bool
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.isXmlHttpRequest",
     *  })
     * @JMS\Type("boolean")
     *
     * @ORM\Column(
     *      name="is_xml_http_request",
     *      type="boolean",
     *      nullable=false,
     *  )
     */
    private $xmlHttpRequest;

    /**
     * @var \DateTime
     *
     * @JMS\Groups({
     *      "Default",
     *      "RequestLog",
     *      "RequestLog.time",
     *  })
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(
     *      name="time",
     *      type="datetime",
     *      nullable=false,
     *  )
     */
    private $time;

    /**
     * RequestLog constructor.
     *
     * @throws  \LogicException
     *
     * @param   Request|null $request
     * @param   Response|null $response
     */
    public function __construct(Request $request = null, Response $response = null)
    {
        $this->id = Uuid::uuid4()->toString();

        if (null !== $request) {
            $this->setClientIp($request->getClientIp());
            $this->setMethod($request->getRealMethod());
            $this->setScheme($request->getScheme());
            $this->setHttpHost($request->getHttpHost());
            $this->setBasePath($request->getBasePath());
            $this->setScript('/' . basename($request->getScriptName()));
            $this->setPath($request->getPathInfo());
            $this->setQueryString($request->getRequestUri());
            $this->setUri($request->getUri());
            $this->setController($this->determineController($request));
            $this->setAction($this->determineAction($request));
            $this->setHeaders($request->headers->all());
            $this->setContentType($request->getMimeType($request->getContentType()));
            $this->setContentTypeShort($request->getContentType());
            $this->setContent($request->getContent());
            $this->setParameters($this->determineParameters($request));
            $this->setXmlHttpRequest($request->isXmlHttpRequest());
            $this->setTime(new \DateTime('now', new \DateTimeZone('UTC')));
        }

        if (null !== $response) {
            $this->setStatusCode($response->getStatusCode());
            $this->setResponseContentLength(mb_strlen($response->getContent()));
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * @param string $clientIp
     * @return RequestLog
     */
    public function setClientIp(string $clientIp): RequestLog
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return RequestLog
     */
    public function setUri(string $uri): RequestLog
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return RequestLog
     */
    public function setMethod(string $method): RequestLog
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * @return RequestLog
     */
    public function setScheme(string $scheme): RequestLog
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpHost(): string
    {
        return $this->httpHost;
    }

    /**
     * @param string $httpHost
     * @return RequestLog
     */
    public function setHttpHost(string $httpHost): RequestLog
    {
        $this->httpHost = $httpHost;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     * @return RequestLog
     */
    public function setBasePath(string $basePath): RequestLog
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string|null $queryString
     * @return RequestLog
     */
    public function setQueryString(string $queryString = null): RequestLog
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * @param array $headers
     * @return RequestLog
     */
    public function setHeaders(array $headers): RequestLog
    {
        // Clean possible sensitive data from parameters
        array_walk($headers, [$this, 'cleanParameters']);

        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return RequestLog
     */
    public function setParameters(array $parameters): RequestLog
    {
        // Clean possible sensitive data from parameters
        array_walk($parameters, [$this, 'cleanParameters']);

        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return RequestLog
     */
    public function setStatusCode(int $statusCode): RequestLog
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getResponseContentLength(): int
    {
        return $this->responseContentLength;
    }

    /**
     * @param int $responseContentLength
     * @return RequestLog
     */
    public function setResponseContentLength(int $responseContentLength): RequestLog
    {
        $this->responseContentLength = $responseContentLength;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return RequestLog
     */
    public function setTime(\DateTime $time): RequestLog
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return RequestLog
     */
    public function setUser(User $user = null): RequestLog
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isXmlHttpRequest(): bool
    {
        return $this->xmlHttpRequest;
    }

    /**
     * @param boolean $xmlHttpRequest
     * @return RequestLog
     */
    public function setXmlHttpRequest(bool $xmlHttpRequest): RequestLog
    {
        $this->xmlHttpRequest = $xmlHttpRequest;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string|null $controller
     *
     * @return RequestLog
     */
    public function setController(string $controller = null): RequestLog
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string|null $action
     *
     * @return RequestLog
     */
    public function setAction(string $action = null): RequestLog
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     *
     * @return RequestLog
     */
    public function setPath(string $path = null): RequestLog
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMasterRequest(): bool
    {
        return $this->masterRequest;
    }

    /**
     * @param boolean $masterRequest
     *
     * @return RequestLog
     */
    public function setMasterRequest(bool $masterRequest): RequestLog
    {
        $this->masterRequest = $masterRequest;

        return $this;
    }

    /**
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * @param string $script
     *
     * @return RequestLog
     */
    public function setScript(string $script): RequestLog
    {
        $this->script = $script;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return RequestLog
     */
    public function setContent(string $content): RequestLog
    {
        $this->content = $this->cleanContent($content);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return RequestLog
     */
    public function setContentType(string $contentType = null): RequestLog
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentTypeShort()
    {
        return $this->contentTypeShort;
    }

    /**
     * @param string $contentTypeShort
     *
     * @return RequestLog
     */
    public function setContentTypeShort(string $contentTypeShort = null): RequestLog
    {
        $this->contentTypeShort = $contentTypeShort;

        return $this;
    }

    /**
     * Helper method to clean parameters / header array of any sensitive data.
     *
     * @param   mixed   $value
     * @param   string  $key
     */
    protected function cleanParameters(&$value, string $key)
    {
        // What keys we should replace so that any sensitive data is not logged
        $replacements = [
            'password'          => '*** REPLACED ***',
            'token'             => '*** REPLACED ***',
            'authorization'     => '*** REPLACED ***',
            'cookie'            => '*** REPLACED ***',
        ];

        // Normalize current key
        $key = mb_strtolower($key);

        // Replace current value
        if (array_key_exists($key, $replacements)) {
            $value = $replacements[$key];
        }

        // Recursive call
        if (is_array($value)) {
            array_walk($value, [$this, 'cleanParameters']);
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function determineController(Request $request): string
    {
        $controller = $request->get('_controller', '');

        if (strpos($controller, '::')) {
            $controller = explode('::', $controller);
            $controller = explode('\\', $controller[0]);

            return $controller[4] ?? '';
        } else {
            $controller = explode(':', $controller);

            return $controller[0];
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function determineAction(Request $request): string
    {
        $action = $request->get('_controller', '');
        $action = strpos($action, '::') ? explode('::', $action) : explode(':', $action);

        return $action[1] ?? '';
    }

    /**
     * Getter method to convert current request parameters to array.
     *
     * @throws  \LogicException
     *
     * @param   Request $request
     *
     * @return  array
     */
    private function determineParameters(Request $request): array
    {
        // Content given so parse it
        if ($request->getContent()) {
            // First try to convert content to array from JSON
            try {
                $output = JSON::decode($request->getContent(), true);
            } catch (\LogicException $error) { // Oh noes content isn't JSON so just parse it
                $output = [];

                parse_str($request->getContent(), $output);
            }
        } else { // Otherwise trust parameter bag
            $output = $request->request->all();
        }

        return (array)$output;
    }

    /**
     * Method to clean raw request content of any sensitive data.
     *
     * @param   string  $content
     *
     * @return  string
     */
    private function cleanContent(string $content): string
    {
        $iterator = function ($search) use (&$content) {
            $content = preg_replace('/(' . $search . '":)"(.*)"/', '$1"*** REPLACED ***"', $content);
        };

        $replacements = [
            'password',
            'token',
            'authorization',
            'cookie',
        ];

        array_map($iterator, $replacements);

        return $content;
    }
}
