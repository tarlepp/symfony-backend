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
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
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
     * @var KernelInterface
     */
    protected $kernel;

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
     * @var bool
     */
    protected $masterRequest;

    /**
     * {@inheritdoc}
     */
    public function __construct(KernelInterface $kernel, Logger $logger, RequestLogService $service)
    {
        // Store user services
        $this->kernel = $kernel;
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
    public function setMasterRequest(bool $masterRequest): ResponseLoggerInterface
    {
        $this->masterRequest = $masterRequest;

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
        $entity = new RequestLogEntity($this->request, $this->response);
        $entity->setUser($this->user);
        $entity->setMasterRequest($this->masterRequest);

        // Store request log and  clean history
        try {
            $this->service->save($entity, true);
            $this->service->getRepository()->cleanHistory();
        } catch (\Exception $error) { // Silently ignore this error to prevent client to get real error
            if ($this->kernel->getEnvironment() === 'dev') {
                throw $error;
            }

            $this->logger->err($error->getMessage());
        }
    }
}
