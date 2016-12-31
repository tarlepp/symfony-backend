<?php
declare(strict_types=1);
/**
 * /src/App/Validator/Constraints/UniqueUsernameValidator.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Validator\Constraints;

use App\DTO\Console\Interfaces\User as UserInterface;
use App\Repository\User as UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueUsernameValidator
 *
 * @package App\Validator\Constraints
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UniqueUsernameValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UniqueUsernameValidator constructor.
     *
     * @param   EntityRepository    $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * In this case check if 'username' is available or not within User repository.
     *
     * @throws  NonUniqueResultException
     *
     * @param   UserInterface $value The value that should be validated
     * @param   Constraint|UniqueUsername $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->repository->isUsernameAvailable($value->getUsername(), $value->getId())) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
