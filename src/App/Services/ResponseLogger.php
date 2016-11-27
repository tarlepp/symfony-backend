<?php
declare(strict_types=1);
/**
 * /src/App/Services/ResponseLogger.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\RequestLog as RequestLogEntity;
use App\Services\Interfaces\ResponseLogger as ResponseLoggerInterface;
use App\Services\Rest\RequestLog as RequestLogService;
use App\Utils\JSON;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ResponseLogger
 *
 * @package App\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseLogger implements ResponseLoggerInterface
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var RequestLogService
     */
    protected $service;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function __construct(Logger $logger, RequestLogService $service)
    {
        // Store user services
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(Response $response): ResponseLoggerInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(Request $request): ResponseLoggerInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user = null): ResponseLoggerInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        // Just check that we have all that we need
        if (!($this->request instanceof Request) || !($this->response instanceof Response)) {
            return;
        }

        // Create new request log entity
        $entity = new RequestLogEntity();
        $entity->setUser($this->user);
        $entity->setClientIp($this->request->getClientIp());
        $entity->setUri($this->request->getUri());
        $entity->setMethod($this->request->getRealMethod());
        $entity->setQueryString($this->request->getRequestUri());
        $entity->setHeaders($this->request->headers->all());
        $entity->setParameters($this->getParameters());
        $entity->setStatusCode($this->response->getStatusCode());
        $entity->setResponseContentLength(mb_strlen($this->response->getContent()));
        $entity->setIsXmlHttpRequest($this->request->isXmlHttpRequest());
        $entity->setTime(new \DateTime('now', new \DateTimeZone('UTC')));

        // Store request log and  clean history
        try {
            $this->service->save($entity);
            $this->service->getRepository()->cleanHistory();
        } catch (\Exception $error) { // Silently ignore this error to prevent client to get real error
            $this->logger->err($error->getMessage());
        }
    }

    /**
     * Getter method to convert current request parameters to array.
     *
     * @return array
     */
    private function getParameters(): array
    {
        // Content given so parse it
        if ($this->request->getContent()) {
            // First try to convert content to array from JSON
            try {
                $output = JSON::decode($this->request->getContent(), true);
            } catch (\LogicException $error) { // Oh noes content isn't JSON so just parse it
                $output = [];

                parse_str($this->request->getContent(), $output);
            }
        } else { // Otherwise trust parameter bag
            $output = $this->request->request->all();
        }

        return $output;
    }
}
