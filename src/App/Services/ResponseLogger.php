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
use Psr\Log\LoggerInterface;
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
     * @var string
     */
    protected $environment;

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
    public function __construct(LoggerInterface $logger, RequestLogService $service, string $environment)
    {
        // Store user services
        $this->logger = $logger;
        $this->service = $service;
        $this->environment = $environment;
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
     * {@inheritDoc}
     */
    public function handle()
    {
        // Just check that we have all that we need
        if (!($this->request instanceof Request) || !($this->response instanceof Response)) {
            return;
        }

        // Store request log and  clean history
        try {
            // Create new request log entity
            $entity = new RequestLogEntity($this->request, $this->response);
            $entity->setUser($this->user);
            $entity->setMasterRequest($this->masterRequest);

            $this->service->save($entity, true);
            $this->service->getRepository()->cleanHistory();
        } catch (\Exception $error) {
            // Silently ignore this error to prevent client to get real error IF not in dev environment
            if ($this->environment === 'dev') {
                throw $error;
            }

            $this->logger->error($error->getMessage());
        }
    }
}
