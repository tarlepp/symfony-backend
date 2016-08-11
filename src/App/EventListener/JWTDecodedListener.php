<?php
/**
 * /src/App/EventListener/JWTDecodedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTDecodedListener
 *
 * @see /app/config/services.yml
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTDecodedListener
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * JWTDecodedListener constructor.
     *
     * @param   RequestStack    $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Event listener method to attach some custom JWT payload checks.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_decoded' event is broadcast.
     *
     * @param   JWTDecodedEvent     $event
     *
     * @return  void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        // Get current payload and request object
        $payload = $event->getPayload();
        $request = $this->requestStack->getCurrentRequest();

        $event->getRequest();

        // Custom checks to validate user's JWT
        if ((!array_key_exists('ip', $payload) || $payload['ip'] !== $request->getClientIp())
            || (!array_key_exists('agent', $payload) || $payload['agent'] !== $request->headers->get('User-Agent'))
        ) {
            $event->markAsInvalid();
        }
    }
}
