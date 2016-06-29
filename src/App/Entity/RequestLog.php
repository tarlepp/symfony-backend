<?php
/**
 * /src/App/Entity/RequestLog.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entity;

use App\Doctrine\Behaviours as ORMBehaviors;
use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * RequestLog
 *
 * @ORM\Table(
 *      name="request_log",
 *      indexes={
 *          @ORM\Index(name="createdBy_id", columns={"createdBy_id"}),
 *          @ORM\Index(name="updatedBy_id", columns={"updatedBy_id"}),
 *          @ORM\Index(name="deletedBy_id", columns={"deletedBy_id"})
 *      }
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repository\RequestLog"
 *  )
 *
 * @package App\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLog implements EntityInterface
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false,
     *  )
     * @ORM\Id()
     * @ORM\GeneratedValue(
     *      strategy="IDENTITY",
     *  )
     */
    private $id;

    /**
     * @var string
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
     * @ORM\Column(
     *      name="headers",
     *      type="array",
     *  )
     */
    private $headers;

    /**
     * @var array
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
     * @ORM\Column(
     *      name="response_content_length",
     *      type="integer",
     *      nullable=false,
     *  )
     */
    private $responseContentLength;

    /**
     * @return int
     */
    public function getId()
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
    public function setClientIp($clientIp)
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
    public function setUri($uri)
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
    public function setMethod($method)
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
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * @param array $headers
     * @return RequestLog
     */
    public function setHeaders($headers)
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
    public function setParameters(array $parameters)
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
    public function setStatusCode($statusCode)
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
    public function setResponseContentLength($responseContentLength)
    {
        $this->responseContentLength = $responseContentLength;

        return $this;
    }

    /**
     * Helper method to clean parameters / header array of any sensitive data.
     *
     * @param   mixed   $value
     * @param   string  $key
     */
    protected function cleanParameters(&$value, $key)
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
