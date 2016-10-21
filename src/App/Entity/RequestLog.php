<?php
declare(strict_types=1);
/**
 * /src/App/Entity/RequestLog.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

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
    private $isXmlHttpRequest;

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
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @param string $clientIp
     * @return RequestLog
     */
    public function setClientIp(string $clientIp) : RequestLog
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return RequestLog
     */
    public function setUri(string $uri) : RequestLog
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return RequestLog
     */
    public function setMethod(string $method) : RequestLog
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $queryString
     * @return RequestLog
     */
    public function setQueryString(string $queryString) : RequestLog
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * @param array $headers
     * @return RequestLog
     */
    public function setHeaders(array $headers) : RequestLog
    {
        // Clean possible sensitive data from parameters
        array_walk($headers, [$this, 'cleanParameters']);

        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return RequestLog
     */
    public function setParameters(array $parameters) : RequestLog
    {
        // Clean possible sensitive data from parameters
        array_walk($parameters, [$this, 'cleanParameters']);

        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return RequestLog
     */
    public function setStatusCode(int $statusCode) : RequestLog
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getResponseContentLength()
    {
        return $this->responseContentLength;
    }

    /**
     * @param int $responseContentLength
     * @return RequestLog
     */
    public function setResponseContentLength(int $responseContentLength) : RequestLog
    {
        $this->responseContentLength = $responseContentLength;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return RequestLog
     */
    public function setTime(\DateTime $time) : RequestLog
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
    public function setUser(User $user = null) : RequestLog
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsXmlHttpRequest() : bool
    {
        return $this->isXmlHttpRequest;
    }

    /**
     * @param boolean $isXmlHttpRequest
     * @return RequestLog
     */
    public function setIsXmlHttpRequest(bool $isXmlHttpRequest) : RequestLog
    {
        $this->isXmlHttpRequest = $isXmlHttpRequest;

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

        // Replace current value
        if (array_key_exists($key, $replacements)) {
            $value = $replacements[$key];
        }

        // Recursive call
        if (is_array($value)) {
            array_walk($value, [$this, 'cleanParameters']);
        }
    }
}
