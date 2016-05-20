<?php
/**
 * /src/App/Form/Console/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Console;

use App\Entity\UserGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User
 *
 * @category    Form
 * @package     App\Form\Console
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class User extends AbstractType
{
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
                    'required'  => true,
                ]
            )
            ->add(
                'userGroups',
                EntityType::class,
                [
                    'label'         => 'User group(s)',
                    'class'         => 'App\Entity\UserGroup',
                    'multiple'      => true,
                    'choice_label'  => function(UserGroup $group) {
                        return $group->getName() . ' [' . $group->getRole() . ']';
                    },
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User'
        ]);
    }
}
