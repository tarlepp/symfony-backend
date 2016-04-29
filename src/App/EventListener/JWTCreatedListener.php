<?php
/**
 * /src/App/EventListener/JWTCreatedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

// Application components
use App\Entity\User;

// 3rd party components
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class JWTCreatedListener
 *
 * @see /app/config/services.yml
 *
 * @category    Listener
 * @package     App\EventListener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTCreatedListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * JWTCreatedListener constructor.
     *
     * @param   ContainerInterface  $container
     *
     * @return  JWTCreatedListener
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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

        /** @var User $user */
        $user = $event->getUser();

        // We need to make sure that User object is right one
        if (!($user instanceof User)) {
            $user = $this->container->get('app.services.user')->loadUserByUsername($payload['username']);
        }

        // Merge payload with user's login data
        $payload = array_merge($payload, $user->getLoginData());

        // And set new payload for JWT
        $event->setData($payload);
    }
}
