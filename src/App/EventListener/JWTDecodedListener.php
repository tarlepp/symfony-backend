<?php
/**
 * /src/App/EventListener/JWTDecodedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

// 3rd party components
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

/**
 * Class JWTDecodedListener
 *
 * @category    Listener
 * @package     App\EventListener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTDecodedListener
{
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
        // Oh noes, cannot get request...
        if (!($request = $event->getRequest())) {
            return;
        }

        // Get current payload and request object
        $payload = $event->getPayload();
        $request = $event->getRequest();

        // Custom checks to validate user's JWT
        if (
            (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp())
            || (!isset($payload['agent']) || $payload['agent'] !== $request->headers->get('User-Agent'))
        ) {
            $event->markAsInvalid();
        }
    }
}