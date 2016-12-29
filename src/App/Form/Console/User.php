<?php
declare(strict_types = 1);
/**
 * /src/App/Form/Console/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Console;

use App\DTO\Console\User as UserDto;
use App\Entity\UserGroup;
use App\Services\Rest\UserGroup as UserGroupService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User
 *
 * @package App\Form\Console
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User extends AbstractType
{
    /**
     * @var UserGroupService
     */
    protected $userGroupService;

    /**
     * Setter for user group service.
     *
     * @param   UserGroupService    $userGroupService
     *
     * @return  void
     */
    public function setUserGroupService(UserGroupService $userGroupService)
    {
        $this->userGroupService = $userGroupService;
    }

    /**
     * Method to create choices array for user groups.
     *
     * @return  array
     */
    public function getUserGroupChoices(): array
    {
        // Initialize output
        $choices = [];

        /**
         * Lambda function to iterate all user groups and to create necessary choices array.
         *
         * @param   UserGroup   $userGroup
         *
         * @return  void
         */
        $iterator = function (UserGroup $userGroup) use (&$choices) {
            $name = $userGroup->getName() . ' [' . $userGroup->getRole() . ']';

            $choices[$name] = $userGroup->getId();
        };

        array_map($iterator, $this->userGroupService->find());

        return $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                Type\TextType::class,
                [
                    'label'     => 'Username',
                    'required'  => true,
                ]
            )
            ->add(
                'firstname',
                Type\TextType::class,
                [
                    'label'     => 'Firstname',
                    'required'  => true,
                ]
            )
            ->add(
                'surname',
                Type\TextType::class,
                [
                    'label'     => 'Surname',
                    'required'  => true,
                ]
            )
            ->add(
                'email',
                Type\EmailType::class,
                [
                    'label'     => 'Email address',
                    'required'  => true,
                ]
            )
            ->add(
                'plainPassword',
                Type\PasswordType::class,
                [
                    'label'     => 'Password',
                ]
            )
            ->add(
                'userGroups',
                Type\ChoiceType::class,
                [
                    'choices'           => $this->getUserGroupChoices(),
                    'multiple'          => true,
                    'required'          => true,
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
        ]);
    }
}
