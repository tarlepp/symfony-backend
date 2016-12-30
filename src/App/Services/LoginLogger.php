<?php
declare(strict_types=1);
/**
 * /src/App/Services/LoginLogger.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\User as UserEntity;
use App\Entity\UserLogin as UserLoginEntity;
use App\Repository\User as UserRepository;
use App\Services\Interfaces\LoginLogger as LoginLoggerInterface;
use App\Services\Rest\UserLogin as UserLoginService;
use DeviceDetector\DeviceDetector;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
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
class LoginLogger implements LoginLoggerInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var UserLoginService
     */
    private $userLoginService;

    /**
     * @var UserRepository
     */
    private $userRepository;

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
     * {@inheritdoc}
     */
    public function __construct(
        LoggerInterface $logger,
        UserLoginService $userLoginService,
        EntityRepository $userRepository,
        RequestStack $requestStack
    ) {
        // Store used services
        $this->logger = $logger;
        $this->userLoginService = $userLoginService;
        $this->userRepository = $userRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user): LoginLoggerInterface
    {
        // We need to make sure that User object is right one
        $user = $user instanceof UserEntity ? $user : $this->userRepository->loadUserByUsername($user->getUsername());

        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
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
     * @throws  \UnexpectedValueException
     *
     * @return  UserLoginEntity
     */
    private function createEntry(): UserLoginEntity
    {
        // Create new login entry
        $userLogin = new UserLoginEntity();
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
        return $this->userLoginService->save($userLogin);
    }
}
