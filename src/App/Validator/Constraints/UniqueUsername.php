<?php
declare(strict_types=1);
/**
 * /src/App/Validator/Constraints/UniqueUsername.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUsername
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @package App\Validator\Constraints
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UniqueUsername extends Constraint
{
    // Unique constant for validator constrain
    const IS_UNIQUE_USERNAME_ERROR = 'ea62740a-4d9b-4a25-9a56-46fb4c3d5fea';

    // Error names configuration
    protected static $errorNames = [
        self::IS_UNIQUE_USERNAME_ERROR => 'IS_UNIQUE_USERNAME_ERROR',
    ];

    // Message for validation error
    public $message = 'This username is already taken.';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
