<?php
/**
 * /src/App/Services/ResponseLogger.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\RequestLog as Entity;
use App\Services\Rest\RequestLog as Service;
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
class ResponseLogger implements Interfaces\ResponseLogger
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
     * @var Service
     */
    protected $service;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * ResponseLogger constructor.
     *
     * @param   Logger  $logger
     * @param   Service $service
     */
    public function __construct(Logger $logger, Service $service)
    {
        // Store user services
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * Setter for response object.
     *
     * @param   Response $response
     *
     * @return  ResponseLogger
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Setter for request object.
     *
     * @param   Request $request
     *
     * @return  ResponseLogger
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Setter method for current user.
     *
     * @param   UserInterface|null $user
     *
     * @return  ResponseLogger
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Method to handle current response and log it to database.
     *
     * @return  void
     */
    public function handle()
    {
        // Just check that we have all that we need
        if (!($this->request instanceof Request) || !($this->response instanceof Response)) {
            return;
        }

        // Create new request log entity
        $entity = new Entity();
        $entity->setUser($this->user);
        $entity->setClientIp($this->request->getClientIp());
        $entity->setUri($this->request->getUri());
        $entity->setMethod($this->request->getRealMethod());
        $entity->setQueryString($this->request->getRequestUri());
        $entity->setHeaders($this->request->headers->all());
        $entity->setParameters($this->getParameters());
        $entity->setStatusCode($this->response->getStatusCode());
        $entity->setResponseContentLength(mb_strlen($this->response->getContent()));
        $entity->setTime(new \DateTime());

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
    private function getParameters()
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
