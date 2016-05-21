<?php
/**
 * /src/App/Doctrine/Listener/UserListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Doctrine\Listener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class UserListener
 *
 * @category    Listener
 * @package     App\Doctrine\Listener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserListener
{
    /**
     * Used encoder factory.
     *
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * Constructor
     *
     * @param   EncoderFactoryInterface $encoderFactory
     *
     * @return  UserListener
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Getter for user password encoder factory.
     *
     * @param   User    $user
     *
     * @return  PasswordEncoderInterface
     */
    public function getEncoder(User $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * Doctrine lifecycle event for 'prePersist' event.
     *
     * @param   LifecycleEventArgs  $event
     *
     * @return  void
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        // Get user entity object
        $user = $event->getEntity();

        // Valid user so lets change password
        if ($user instanceof User) {
            $this->changePassword($user);
        }
    }

    /**
     * Doctrine lifecycle event for 'preUpdate' event.
     *
     * @param   PreUpdateEventArgs  $event
     *
     * @return  void
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        // Get user entity object
        $user = $event->getEntity();

        // Valid user so lets change password
        if ($user instanceof User) {
            $this->changePassword($user);

            $event->setNewValue('password', $user->getPassword());
        }
    }

    /**
     * Method to change user password whenever it's needed.
     *
     * @param   User $user
     *
     * @return  void
     */
    protected function changePassword(User $user)
    {
        // Get plain password from user entity
        $plainPassword = $user->getPlainPassword();

        // Yeah, we have new plain password set, so we need to encode it
        if (!empty($plainPassword)) {
            $encoder = $this->getEncoder($user);

            // Password hash callback
            $callback = function($plainPassword) use ($encoder, $user) {
                return $encoder->encodePassword($plainPassword, $user->getSalt());
            };

            // Set new password and encode it with user encoder
            $user->setPassword($callback, $plainPassword);

            // And clean up plain password from entity
            $user->eraseCredentials();
        }
    }
}
