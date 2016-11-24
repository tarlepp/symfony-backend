<?php
declare(strict_types = 1);
/**
 * /src/App/ApiDoc/Auth/GetTokenInput.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\ApiDoc\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GetTokenInput
 *
 * @package App\ApiDoc\Auth
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GetTokenInput extends AbstractType
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
                    'label' => 'Username',
                    'required' => true,
                ]
            )
            ->add(
                'password',
                Type\TextType::class,
                [
                    'required' => true,
                ]
            )
        ;
    }
}
