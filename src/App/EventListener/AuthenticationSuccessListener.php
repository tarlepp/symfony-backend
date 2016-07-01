<?php
/**
 * /src/App/EventListener/AuthenticationSuccessListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Entity\UserLogin as Entity;
use App\Services\Rest\UserLogin as Service;
use DeviceDetector\DeviceDetector;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AuthenticationSuccessListener
 *
 * @see /app/config/services_listeners.yml
 *
 * @category    Listener
 * @package     App\EventListener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthenticationSuccessListener
{
    /**
     * @var ContainerInterface
     */
    protected $service;

    /**
     * AuthenticationSuccessListener constructor.
     *
     * @param   Service  $service
     *
     * @return  AuthenticationSuccessListener
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
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
        // Get request object
        $request = $event->getRequest();

        // Specify user agent
        $agent = $request->headers->get('User-Agent');

        // Parse user agent data with device detector
        $deviceDetector = new DeviceDetector($agent);
        $deviceDetector->parse();

        // Create new login entry
        $userLogin = new Entity();
        $userLogin->setUser($event->getUser());
        $userLogin->setIp($request->getClientIp());
        $userLogin->setHost($request->getHost());
        $userLogin->setAgent($agent);
        $userLogin->setClientType($deviceDetector->getClient('type'));
        $userLogin->setClientName($deviceDetector->getClient('name'));
        $userLogin->setClientShortName($deviceDetector->getClient('short_name'));
        $userLogin->setClientVersion($deviceDetector->getClient('version'));
        $userLogin->setClientEngine($deviceDetector->getClient('engine'));
        $userLogin->setOsName($deviceDetector->getOs('name'));
        $userLogin->setOsShortName($deviceDetector->getOs('short_name'));
        $userLogin->setOsVersion($deviceDetector->getOs('version'));
        $userLogin->setOsPlatform($deviceDetector->getOs('platform'));
        $userLogin->setDeviceName($deviceDetector->getDeviceName());
        $userLogin->setBrandName($deviceDetector->getBrandName());
        $userLogin->setModel($deviceDetector->getModel());
        $userLogin->setLoginTime(new \DateTime());

        // And store entry to database
        $this->service->save($userLogin);
    }
}
