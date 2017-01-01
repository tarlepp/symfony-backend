<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/ApiDoc/Auth/GetTokenInputTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\ApiDoc\Auth;

use App\ApiDoc\Auth\GetTokenInput;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class GetTokenInputTest
 *
 * @package AppBundle\integration\ApiDoc\Auth
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GetTokenInputTest extends TypeTestCase
{
    /**
     * Test that submit with valid data works as expected.
     */
    public function testSubmitValidData()
    {
        $formData = [
            'username'  => 'foo',
            'password'  => 'bar',
        ];

        $form = $this->factory->create(GetTokenInput::class);
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            static::assertArrayHasKey($key, $children);
        }
    }

    /**
     * Test that submit with invalid data works as expected.
     *
     * @dataProvider dataProviderTestSubmitInvalidData
     *
     * @param array $formData
     * @param array $expectedFormData
     */
    public function testSubmitInvalidData(array $formData, array $expectedFormData)
    {
        $form = $this->factory->create(GetTokenInput::class);
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expectedFormData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            static::assertArrayHasKey($key, $children);
        }
    }

    /**
     * Data provider for 'testSubmitInvalidData' test.
     *
     * @return array
     */
    public function dataProviderTestSubmitInvalidData(): array
    {
        return [
            [
                [],
                [
                    'username'  => null,
                    'password'  => null,
                ]
            ],
            [
                [
                    'username'  => 'foo',
                ],
                [
                    'username'  => 'foo',
                    'password'  => null,
                ],
            ],
            [
                [
                    'password'  => 'foo',
                ],
                [
                    'username'  => null,
                    'password'  => 'foo',
                ],
            ],
        ];
    }
}
