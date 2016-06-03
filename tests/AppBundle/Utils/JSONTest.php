<?php
/**
 * /tests/AppBundle/Utils/JSONTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace Tests\AppBundle\Utils;

use App\Tests\WebTestCase;

/**
 * Class JSONTest
 *
 * @package Tests\AppBundle\Utils
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JSONTest extends WebTestCase
{
    /**
     * @dataProvider dataProviderTestThatEncodeWorksLikeExpected
     *
     * @param   array $value
     * @param   mixed $expected
     */
    public function testThatEncodeWorksLikeExpected($value, $expected)
    {
        $this->assertEquals($expected, call_user_func('\App\Utils\JSON::encode', $value));
    }

    /**
     * @dataProvider dataProviderTestThatDecodeWorksLikeExpected
     *
     * @param   array $parameters
     * @param   mixed $expected
     */
    public function testThatDecodeWorksLikeExpected($parameters, $expected)
    {
        $this->assertEquals($expected, call_user_func_array('\App\Utils\JSON::decode', $parameters));
    }

    /**
     * Data provider for 'testThatEncodeWorksLikeExpected'
     *
     * @return array
     */
    public function dataProviderTestThatEncodeWorksLikeExpected()
    {
        // Create simple object for test
        $object = new \stdClass();
        $object->bar = 'foo';
        $object->foo = new \stdClass();
        $object->foo->a = 'foobar';
        $object->foo->b = 12;
        $object->foo->c = "12";
        $object->foo->d = true;

        return [
            [
                null,
                'null',
            ],
            [
                true,
                'true',
            ],
            [
                false,
                'false',
            ],
            [
                ['foo' => 'bar'],
                '{"foo":"bar"}',
            ],
            [
                $object,
                '{"bar":"foo","foo":{"a":"foobar","b":12,"c":"12","d":true}}',
            ],
        ];
    }

    /**
     * Data provider for 'testThatDecodeWorksLikeExpected'
     *
     * @return array
     */
    public function dataProviderTestThatDecodeWorksLikeExpected()
    {
        $iterator = function ($data) {
            return [
                [$data[1], is_array($data[0]) ? true : false],
                $data[0],
            ];
        };

        return array_map($iterator, $this->dataProviderTestThatEncodeWorksLikeExpected());
    }
}
