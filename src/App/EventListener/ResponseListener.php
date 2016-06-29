<?php
/**
 * /src/App/EventListener/ResponseListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Services\Interfaces\ResponseLogger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class ResponseListener
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseListener
{
    /**
     * @var ResponseLogger
     */
    protected $logger;

    /**
     * ExceptionListener constructor.
     *
     * @param ResponseLogger $responseLogger
     */
    public function __construct(ResponseLogger $responseLogger)
    {
        $this->logger = $responseLogger;
    }

    /**
     * Event listener method to log every request / response.
     *
     * @param   FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->logger->setRequest($event->getRequest());
        $this->logger->setResponse($event->getResponse());
        $this->logger->handle();
    }
}
