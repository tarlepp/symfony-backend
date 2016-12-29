<?php
declare(strict_types=1);
/**
 * /src/App/EventListener/JWTCreatedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Entity\User;
use App\Services\Rest\User as UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class JWTCreatedListener
 *
 * @see /app/config/services.yml
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JWTCreatedListener
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var RoleHierarchy
     */
    protected $roleHierarchy;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * JWTCreatedListener constructor.
     *
     * @param   UserService     $userService
     * @param   RoleHierarchy   $roleHierarchy
     * @param   RequestStack    $requestStack
     */
    public function __construct(UserService $userService, RoleHierarchy $roleHierarchy, RequestStack $requestStack)
    {
        $this->userService = $userService;
        $this->roleHierarchy = $roleHierarchy;
        $this->requestStack = $requestStack;
    }

    /**
     * Event listener method to attach some custom data to JWT payload.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_created' event is broadcast.
     *
     * @throws  UsernameNotFoundException
     *
     * @param   JWTCreatedEvent $event
     *
     * @return  void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        // Get current original payload
        $payload = $event->getData();

        // Get User entity
        $user = $this->getUser($event, (string)$payload['username']);

        // Update JWT expiration data
        $this->setExpiration($payload);

        // Add some extra security data to payload
        $this->setSecurityData($payload, $this->requestStack->getCurrentRequest(), $user);

        // Add necessary user data to payload
        $this->setUserData($payload, $user);

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
     * @param   User    $user
     *
     * @return  void
     */
    private function setSecurityData(array &$payload, Request $request, User $user)
    {
        $payload['ip'] = $request->getClientIp();
        $payload['agent'] = $request->headers->get('User-Agent');
        $payload['checksum'] = $user->getChecksum();
    }

    /**
     * Method to add all necessary user information to JWT payload.
     *
     * Magic thing here is to determine all user's roles to single dimensional array, which is used on the frontend
     * side application to check access to different routes.
     *
     * @param   array   $payload
     * @param   User    $user
     *
     * @return  void
     */
    private function setUserData(array &$payload, User $user)
    {
        // Determine all roles for current user
        $payload['roles'] = array_unique(
            array_map(
                function (RoleInterface $role) {
                    return $role->getRole();
                },
                $this->roleHierarchy->getReachableRoles($user->getUserGroups()->toArray())
            )
        );

        // Merge payload with user's login data
        $payload = array_merge($payload, $user->getLoginData());
    }

    /**
     * Method to get user entity from current event.
     *
     * @throws  UsernameNotFoundException
     *
     * @param   JWTCreatedEvent $event
     * @param   string $username
     *
     * @return  User
     */
    private function getUser(JWTCreatedEvent $event, string $username): User
    {
        /** @var User $user */
        $user = $event->getUser();

        // We need to make sure that User object is right one
        if (!($user instanceof User)) {
            $user = $this->userService
                ->getRepository()
                ->loadUserByUsername($username)
            ;
        }

        return $user;
    }
}
