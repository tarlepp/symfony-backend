<?php
/**
 * /src/App/EventListener/JWTCreatedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\RoleInterface;

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

        // Update JWT expiration data
        $this->setExpiration($payload);

        // Add some extra security data to payload
        $this->setSecurityData($payload, $request);

        // Add necessary user data to payload
        $this->setUserData($payload, $event);

        // And set new payload for JWT
        $event->setData($payload);
    }

    /**
     * Method to set/modify JWT expiration date dynamically.
     *
     * @param   array   $payload
     *
     * @return  void
     */
    private function setExpiration(array &$payload)
    {
        // Set new exp value for JWT
        $expiration = new \DateTime('+1 day');

        $payload['exp'] = $expiration->getTimestamp();
    }

    /**
     * Method to add some security related data to JWT payload, which are checked on JWT decode process.
     *
     * @see JWTDecodedListener
     *
     * @param   array   $payload
     * @param   Request $request
     *
     * @return  void
     */
    private function setSecurityData(array &$payload, Request $request)
    {
        $payload['ip'] = $request->getClientIp();
        $payload['agent'] = $request->headers->get('User-Agent');
    }

    /**
     * Method to add all necessary user information to JWT payload.
     *
     * Magic thing here is to determine all user's roles to single dimensional array, which is used on the frontend
     * side application to check access to different routes.
     *
     * @param   array           $payload
     * @param   JWTCreatedEvent $event
     *
     * @return  void
     */
    private function setUserData(array &$payload, JWTCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();

        // We need to make sure that User object is right one
        if (!($user instanceof User)) {
            $user = $this->container->get('app.services.user')->getByUsername($payload['username']);
        }

        // Determine all roles for current user
        $payload['roles'] = array_unique(
            array_map(
                function (RoleInterface $role) {
                    return $role->getRole();
                },
                $this->container->get('security.role_hierarchy')->getReachableRoles($user->getUserGroups()->toArray())
            )
        );

        // Merge payload with user's login data
        $payload = array_merge($payload, $user->getLoginData());
    }
}
