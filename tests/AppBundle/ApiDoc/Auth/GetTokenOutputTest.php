<?php
/**
 * /tests/AppBundle/ApiDoc/Auth/GetTokenOutputTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\ApiDoc\Auth;

use App\ApiDoc\Auth\GetTokenOutput;
use Symfony\Component\Form\Test\TypeTestCase;

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

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
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

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedFormData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * Data provider for 'testSubmitInvalidData' test.
     *
     * @return array
     */
    public function dataProviderTestSubmitInvalidData()
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
