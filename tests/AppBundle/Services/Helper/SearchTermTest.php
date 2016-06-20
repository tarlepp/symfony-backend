<?php
/**
 * /tests/AppBundle/Services/Helper/SearchTermTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace Tests\AppBundle\Services\Helpers;

use App\Services\Helper\SearchTerm;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class SearchTermTest
 *
 * @package Tests\AppBundle\Utils\Helpers
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SearchTermTest extends KernelTestCase
{
    /**
     * @var SearchTerm
     */
    protected static $service;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();

        self::$service = static::$kernel->getContainer()->get('app.services.helper.search_term');
    }

    /**
     * @dataProvider dataProviderTestThatWithoutColumnOrSearchTermCriteriaIsNull
     *
     * @param   mixed   $column
     * @param   mixed   $search
     */
    public function testThatWithoutColumnOrSearchTermCriteriaIsNull($column, $search)
    {
        $this->assertNull(self::$service->getCriteria($column, $search), 'Criteria was not NULL with given parameters');
    }

    /**
     * @dataProvider dataProviderTestThatReturnedCriteriaIsExpected
     *
     * @param array $inputArguments
     * @param array $expected
     */
    public function testThatReturnedCriteriaIsExpected(array $inputArguments, array $expected)
    {
        $this->assertEquals($expected, call_user_func_array([self::$service, 'getCriteria'], $inputArguments));
    }

    /**
     * Data provider for testThatWithoutColumnOrSearchTermCriteriaIsNull
     *
     * @return array
     */
    public function dataProviderTestThatWithoutColumnOrSearchTermCriteriaIsNull()
    {
        return [
            [null, null],
            ['foo', null],
            [null, 'foo'],
            ['', ''],
            [' ', ''],
            ['', ' '],
            [' ', ' '],
            ['foo', ''],
            ['foo', ' '],
            ['', 'foo'],
            [' ', 'foo'],
            [[], []],
            [[null], [null]],
            [['foo'], [null]],
            [[null], ['foo']],
            [[''], ['']],
            [[' '], ['']],
            [[''], [' ']],
            [[' '], [' ']],
            [['foo'], ['']],
            [['foo'], [' ']],
            [[''], ['foo']],
            [[' '], ['foo']],
        ];
    }

    /**
     * Data provider for testThatReturnedCriteriaIsExpected
     *
     * @return array
     */
    public function dataProviderTestThatReturnedCriteriaIsExpected()
    {
        return [
            [
                ['c1', 'word'],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                [['c1', 'c2'], ['search', 'word']],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c2', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                            ['entity.c2', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                [['c1', 'c2'], 'search word'],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c2', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                            ['entity.c2', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['someTable.c1', 'search word'],
                [
                    'and' => [
                        'or' => [
                            ['someTable.c1', 'like', '%search%'],
                            ['someTable.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['someTable.c1', ['search', 'word']],
                [
                    'and' => [
                        'or' => [
                            ['someTable.c1', 'like', '%search%'],
                            ['someTable.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                [['c1', 'someTable.c1'], 'search word'],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['someTable.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                            ['someTable.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                [['c1', 'someTable.c1'], ['search', 'word']],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['someTable.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                            ['someTable.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_AND],
                [
                    'and' => [
                        'and' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_OR],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', 'notSupportedOperand'],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_OR, SearchTerm::MODE_FULL],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_OR, SearchTerm::MODE_STARTS_WITH],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', 'search%'],
                            ['entity.c1', 'like', 'word%'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_OR, SearchTerm::MODE_ENDS_WITH],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search'],
                            ['entity.c1', 'like', '%word'],
                        ],
                    ],
                ],
            ],
            [
                ['c1', 'search word', SearchTerm::OPERAND_OR, 'notSupportedMode'],
                [
                    'and' => [
                        'or' => [
                            ['entity.c1', 'like', '%search%'],
                            ['entity.c1', 'like', '%word%'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
