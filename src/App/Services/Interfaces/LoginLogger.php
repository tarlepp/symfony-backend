<?php
declare(strict_types=1);
/**
 * /src/App/Services/Interfaces/LoginLogger.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

use App\Services\Rest\UserLogin as UserLoginService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface LoginLogger
 *
 * @package App\Services\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface LoginLogger
{
    /**
     * LoginLogger constructor.
     *
     * @param   LoggerInterface     $logger
     * @param   UserLoginService    $userLoginService
     * @param   EntityRepository    $userRepository
     * @param   RequestStack        $requestStack
     */
    public function __construct(
        LoggerInterface $logger,
        UserLoginService $userLoginService,
        EntityRepository $userRepository,
        RequestStack $requestStack
    );

    /**
     * Setter for User object
     *
     * @throws  \UnexpectedValueException
     * @throws  NonUniqueResultException
     * @throws  UsernameNotFoundException
     *
     * @param   UserInterface   $user
     *
     * @return  LoginLogger
     */
    public function setUser(UserInterface $user): LoginLogger;

    /**
     * Method to handle login event.
     *
     * @return  void
     */
    public function handle();
}
