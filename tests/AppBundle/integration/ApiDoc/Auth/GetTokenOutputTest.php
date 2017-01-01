<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/ApiDoc/Auth/GetTokenOutputTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\ApiDoc\Auth;

use App\ApiDoc\Auth\GetTokenOutput;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class GetTokenOutputTest
 *
 * @package AppBundle\integration\ApiDoc\Auth
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GetTokenOutputTest extends TypeTestCase
{
    /**
     * Test that submit with valid data works as expected.
     */
    public function testSubmitValidData()
    {
        $formData = [
            'token'         => 'foo',
            'refresh_token' => 'bar',
        ];

        $form = $this->factory->create(GetTokenOutput::class);
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
        $form = $this->factory->create(GetTokenOutput::class);
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
                    'token'         => null,
                    'refresh_token' => null,
                ]
            ],
            [
                [
                    'token' => 'foo',
                ],
                [
                    'token'         => 'foo',
                    'refresh_token' => null,
                ],
            ],
            [
                [
                    'refresh_token' => 'foo',
                ],
                [
                    'token'         => null,
                    'refresh_token' => 'foo',
                ],
            ],
        ];
    }
}
