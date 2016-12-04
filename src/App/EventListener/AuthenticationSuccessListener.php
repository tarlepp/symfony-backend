<?php
declare(strict_types=1);
/**
 * /src/App/EventListener/AuthenticationSuccessListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Services\Interfaces\LoginLogger;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * Class AuthenticationSuccessListener
 *
 * @see /app/config/services_listeners.yml
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthenticationSuccessListener
{
    /**
     * @var LoginLogger
     */
    protected $loginLogger;

    /**
     * AuthenticationSuccessListener constructor.
     *
     * @param   LoginLogger $loginLogger
     */
    public function __construct(LoginLogger $loginLogger)
    {
        $this->loginLogger = $loginLogger;
    }

    /**
     * Event listener method to log user logins to database.
     *
     * This method is called when 'lexik_jwt_authentication.on_authentication_success' event is broadcast.
     *
     * @param   AuthenticationSuccessEvent  $event
     *
     * @return  void
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        // Set user to LoginLogger class
        $this->loginLogger->setUser($event->getUser());

        // Handle login logger
        $this->loginLogger->handle();
    }
}
