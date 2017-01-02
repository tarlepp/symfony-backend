<?php
declare(strict_types = 1);
/**
 * /src/App/ApiDoc/Auth/GetTokenOutput.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\ApiDoc\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GetTokenOutput
 *
 * @package App\ApiDoc\Auth
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GetTokenOutput extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'token',
                Type\TextType::class,
                [
                    'label'     => 'Username',
                    'required'  => true,
                ]
            )
            ->add(
                'refresh_token',
                Type\TextType::class,
                [
                    'required'  => true,
                ]
            )
        ;
    }
}
