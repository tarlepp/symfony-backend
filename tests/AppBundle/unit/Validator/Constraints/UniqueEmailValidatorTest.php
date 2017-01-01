<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/Validator/Constraints/UniqueEmailValidatorTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\Validator\Constraints;

use App\DTO\Console\User;
use App\Tests\ContainerTestCase;
use App\Validator\Constraints\UniqueEmail;
use App\Validator\Constraints\UniqueEmailValidator;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Class UniqueEmailValidatorTest
 *
 * @package AppBundle\unit\Validator\Constraints
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UniqueEmailValidatorTest extends ContainerTestCase
{
    /**
     * Configure a SomeConstraintValidator.
     *
     * @param string $expectedMessage The expected message on a validation violation, if any.
     *
     * @return UniqueEmailValidator
     */
    public function configureValidator($expectedMessage = null)
    {
        // mock the violation builder
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock()
        ;

        // mock the validator context
        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock()
        ;

        if ($expectedMessage) {
            $builder->expects(static::once())
                ->method('addViolation')
            ;

            $context->expects(static::once())
                ->method('buildViolation')
                ->with(static::equalTo($expectedMessage))
                ->will(static::returnValue($builder))
            ;
        } else {
            $context->expects(static::never())
                ->method('buildViolation')
            ;
        }

        /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface $context */

        // initialize the validator with the mocked context
        $validator = new UniqueEmailValidator($this->getContainer()->get('repository.user'));
        $validator->initialize($context);

        // return the UniqueEmailValidator
        return $validator;
    }

    /**
     * Verify a constraint message is triggered when value is invalid.
     */
    public function testValidateOnInvalid()
    {
        $constraint = new UniqueEmail();
        $validator = $this->configureValidator('This email is already taken.');

        $dto = new User();
        $dto->email = 'john.doe@test.com';

        $validator->validate($dto, $constraint);
    }

    /**
     * Verify no constraint message is triggered when value is valid.
     */
    public function testValidateOnValid()
    {
        $constraint = new UniqueEmail();
        $validator = $this->configureValidator();

        $dto = new User();
        $dto->email = 'foo@bar.com';

        $validator->validate($dto, $constraint);
    }

    /**
     * Verify no constraint message is triggered when value is valid for existing user.
     */
    public function testValidateOnExistingValid()
    {
        $constraint = new UniqueEmail();
        $validator = $this->configureValidator();

        // Fetch user entity
        $user = $this->getContainer()
            ->get('app.services.rest.user')
            ->getRepository()
            ->loadUserByUsername('john.doe@test.com')
        ;

        // Create new DTO from fetched entity
        $dto = new User();
        $dto->loadFromEntity($user);

        $validator->validate($dto, $constraint);
    }
}
