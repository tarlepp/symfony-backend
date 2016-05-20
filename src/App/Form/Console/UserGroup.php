<?php
/**
 * /src/App/Form/Console/UserGroup.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Console;

use App\Services\Helper\Roles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserGroup
 *
 * @category    Form
 * @package     App\Form\Console
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroup extends AbstractType
{
    /**
     * Roles helper service.
     *
     * @var Roles
     */
    private $roles;

    /**
     * Setter for helper roles service.
     *
     * @see /app/config/services_form.yml
     *
     * @param   Roles   $roles
     *
     * @return  void
     */
    public function setHelperRoles(Roles $roles)
    {
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = $this->roles;

        $getRoleLabel = function($role) use ($helper) {
            return $helper->getRoleLabel($role);
        };

        $builder
            ->add(
                'name',
                Type\TextType::class,
                [
                    'label'     => 'Group name',
                    'required'  => true,
                ]
            )
            ->add(
                'role',
                Type\ChoiceType::class,
                [
                    'choices'       => $this->roles->getRoles(),
                    'choice_label'  => $getRoleLabel,
                    'required'      => true,
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
            'data_class' => 'App\Entity\UserGroup'
        ]);
    }
}
