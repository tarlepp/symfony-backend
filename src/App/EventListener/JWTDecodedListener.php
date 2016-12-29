<?php
declare(strict_types=1);
/**
 * /src/App/EventListener/JWTDecodedListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Entity\User as UserEntity;
use App\Services\Rest\User as UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

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
     * @var UserService
     */
    protected $userService;

    /**
     * JWTDecodedListener constructor.
     *
     * @param   RequestStack    $requestStack
     * @param   UserService     $userService
     */
    public function __construct(RequestStack $requestStack, UserService $userService)
    {
        $this->requestStack = $requestStack;
        $this->userService = $userService;
    }

    /**
     * Event listener method to attach some custom JWT payload checks.
     *
     * This method is called when 'lexik_jwt_authentication.on_jwt_decoded' event is broadcast.
     *
     * @throws  UsernameNotFoundException
     *
     * @param   JWTDecodedEvent $event
     *
     * @return  void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        // Get current payload and request object
        $payload = $event->getPayload();
        $request = $this->requestStack->getCurrentRequest();

        /** @var UserEntity $user */
        $user = $this->userService->getRepository()->loadUserByUsername($payload['username']);

        // Custom checks to validate user's JWT
        if ((!array_key_exists('ip', $payload) || $payload['ip'] !== $request->getClientIp())
            || (!array_key_exists('agent', $payload) || $payload['agent'] !== $request->headers->get('User-Agent'))
            || (!array_key_exists('checksum', $payload) || $payload['checksum'] !== $user->getChecksum())
        ) {
            $event->markAsInvalid();
        }
    }
}
