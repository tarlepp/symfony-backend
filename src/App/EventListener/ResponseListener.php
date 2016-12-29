<?php
declare(strict_types = 1);
/**
 * /src/App/EventListener/ResponseListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Services\Interfaces\ResponseLogger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @var UserInterface|null
     */
    protected $user;

    /**
     * ExceptionListener constructor.
     *
     * @param   ResponseLogger          $responseLogger
     * @param   TokenStorageInterface   $tokenStorage
     */
    public function __construct(ResponseLogger $responseLogger, TokenStorageInterface $tokenStorage)
    {
        // Store logger service
        $this->logger = $responseLogger;

        $token = $tokenStorage->getToken();

        // We don't have valid user atm, so set user to null
        if (null === $token || $token instanceof AnonymousToken) {
            $this->user = null;
        } else { // Otherwise get user object
            $this->user = $tokenStorage->getToken()->getUser();
        }
    }

    /**
     * Event listener method to log every request / response.
     *
     * @throws  \Exception
     *
     * @param   FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequest()->getRealMethod() === 'OPTIONS') {
            return;
        }

        // Set needed data to logger and handle actual log
        $this->logger->setRequest($event->getRequest());
        $this->logger->setResponse($event->getResponse());
        $this->logger->setUser($this->user);
        $this->logger->setMasterRequest($event->isMasterRequest());
        $this->logger->handle();
    }
}
