<?php
/**
 * /src/App/EventListener/JWTCreatedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

// 3rd party components
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

/**
 * Class JWTCreatedListener
 *
 * @category    Listener
 * @package     App\EventListener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTCreatedListener
{
    /**
     * Event listener method to attach some custom data to JWT payload.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_created' event is broadcast.
     *
     * @param   JWTCreatedEvent $event
     *
     * @return  void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        // Oh noes, cannot get request...
        if (!($request = $event->getRequest())) {
            return;
        }

        // Get current original payload
        $payload = $event->getData();

        // Set new exp value for JWT
        $expiration = new \DateTime('+1 day');

        $payload['exp'] = $expiration->getTimestamp();

        // Set some security check data to payload
        $payload['ip'] = $request->getClientIp();
        $payload['agent'] = $request->headers->get('User-Agent');

        /** @var \App\Entity\User $user */
        $user = $event->getUser();

        // Attach user login data information to payload
        $payload = array_merge($payload, $user->getLoginData());

        // And set new payload for JWT
        $event->setData($payload);
    }
}
