<?php
/**
 * /tests/AppBundle/Utils/Helpers/SearchTermTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace Tests\AppBundle\Utils\Helpers;

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
     * @dataProvider dataProviderTestThatInvalid
     *
     * @param   mixed   $column
     * @param   mixed   $search
     */
    public function testThatWithoutColumnOrSearchTermCriteriaIsNull($column, $search)
    {
        $this->assertNull(self::$service->getCriteria($column, $search), 'Criteria was not NULL with given parameters');
    }

    /**
     * Data provider for testThatWithoutColumnOrSearchTermCriteriaIsNull
     *
     * @return array
     */
    public function dataProviderTestThatInvalid()
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
}
