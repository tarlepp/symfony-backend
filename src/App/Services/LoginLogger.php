<?php
declare(strict_types=1);
/**
 * /src/App/Services/LoginLogger.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\UserLogin as Entity;
use App\Services\Rest\UserLogin;
use DeviceDetector\DeviceDetector;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LoginLogger
 *
 * @package App\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class LoginLogger
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var UserLogin
     */
    private $service;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $agent;

    /**
     * @var DeviceDetector
     */
    private $deviceDetector;

    /**
     * LoginLogger constructor.
     *
     * @param   Logger          $logger
     * @param   UserLogin       $service
     * @param   RequestStack    $requestStack
     *
     * @return  LoginLogger
     */
    public function __construct(Logger $logger, UserLogin $service, RequestStack $requestStack)
    {
        // Store used services
        $this->logger = $logger;
        $this->service = $service;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Setter for User object
     *
     * @param   UserInterface   $user
     *
     * @return  LoginLogger
     */
    public function setUser(UserInterface $user) : LoginLogger
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Method to handle login event.
     *
     * @return  void
     */
    public function handle()
    {
        // Specify user agent
        $this->agent = $this->request->headers->get('User-Agent');

        // Parse user agent data with device detector
        $this->deviceDetector = new DeviceDetector($this->agent);
        $this->deviceDetector->parse();

        // Create entry
        $this->createEntry();

        $this->logger->debug('Created new login entry to database.');
    }

    /**
     * Method to create new login entry and store it to database.
     *
     * @return Entity
     */
    private function createEntry()
    {
        // Create new login entry
        $userLogin = new Entity();
        $userLogin->setUser($this->user);
        $userLogin->setIp($this->request->getClientIp());
        $userLogin->setHost($this->request->getHost());
        $userLogin->setAgent($this->agent);
        $userLogin->setClientType($this->deviceDetector->getClient('type'));
        $userLogin->setClientName($this->deviceDetector->getClient('name'));
        $userLogin->setClientShortName($this->deviceDetector->getClient('short_name'));
        $userLogin->setClientVersion($this->deviceDetector->getClient('version'));
        $userLogin->setClientEngine($this->deviceDetector->getClient('engine'));
        $userLogin->setOsName($this->deviceDetector->getOs('name'));
        $userLogin->setOsShortName($this->deviceDetector->getOs('short_name'));
        $userLogin->setOsVersion($this->deviceDetector->getOs('version'));
        $userLogin->setOsPlatform($this->deviceDetector->getOs('platform'));
        $userLogin->setDeviceName($this->deviceDetector->getDeviceName());
        $userLogin->setBrandName($this->deviceDetector->getBrandName());
        $userLogin->setModel($this->deviceDetector->getModel());
        $userLogin->setLoginTime(new \DateTime('now', new \DateTimeZone('UTC')));

        // And store entry to database
        return $this->service->save($userLogin);
    }
}
