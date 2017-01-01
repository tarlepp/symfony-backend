<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Doctrine/DBAL/Types/UTCDateTimeTypeTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\UTCDateTimeType;
use App\Tests\ContainerTestCase;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class UTCDateTimeTypeTest
 *
 * @package AppBundle\integration\Doctrine\DBAL\Types
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UTCDateTimeTypeTest extends ContainerTestCase
{
    /**
     * @var AbstractPlatform
     */
    protected $platform;

    /**
     * @var UTCDateTimeType
     */
    protected $type;

    public function setUp()
    {
        parent::setUp();

        // Get platform
        $this->platform = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->getDatabasePlatform()
        ;

        // Get the type object
        $this->type = UTCDateTimeType::getType('datetime');
    }

    public function tearDown()
    {
        static::$kernel->shutdown();

        parent::tearDown();
    }

    /**
     * @dataProvider dataProviderTestThatConvertToDatabaseValueMethodWorks
     *
     * @param   \DateTime   $input
     * @param   string      $expected
     */
    public function testThatConvertToDatabaseValueMethodWorks(\DateTime $input, $expected)
    {
        static::assertEquals(
            $expected,
            $this->type->convertToDatabaseValue($input, $this->platform),
            'Given DateTime object was not converted to expected database value.'
        );
    }

    /**
     * @dataProvider dataProviderTestThatConvertToPHPValueMethodWorks
     *
     * @throws  \Doctrine\DBAL\Types\ConversionException
     *
     * @param   string      $input
     * @param   \DateTime   $expected
     */
    public function testThatConvertToPHPValueMethodWorks($input, \DateTime $expected)
    {
        $value = $this->type->convertToPHPValue($input, $this->platform);

        static::assertInstanceOf(\DateTime::class, $value, 'Value was not converted to proper \DateTime object.');
        static::assertEquals('UTC', $value->getTimezone()->getName(), 'Converted value is not in UTC timezone.');
        static::assertEquals($expected, $value, 'Converted value does not match with expected one.');
    }

    /**
     * @dataProvider dataProviderTestThatConvertToPHPValueMethodThrowsAnError
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     *
     * @throws  \Doctrine\DBAL\Types\ConversionException
     *
     * @param   string  $input
     */
    public function testThatConvertToPHPValueMethodThrowsAnError($input)
    {
        $this->type->convertToPHPValue($input, $this->platform);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatConvertToDatabaseValueMethodWorks(): array
    {
        return self::getTestValues();
    }

    /**
     * @return array
     */
    public function dataProviderTestThatConvertToPHPValueMethodWorks(): array
    {
        $iterator = function (array $values) {
            return [
                $values[1],
                $values[0],
            ];
        };

        return array_map($iterator, self::getTestValues());
    }

    /**
     * @return array
     */
    public function dataProviderTestThatConvertToPHPValueMethodThrowsAnError(): array
    {
        return [
            ['2016-03-05 xx 16:35:00'],
            ['2016-03-xx 16:35:00'],
            [''],
        ];
    }

    /**
     * @return array
     */
    private static function getTestValues(): array
    {
        return [
            [
                new \DateTime('2016-03-05 18:35:00', new \DateTimeZone('Europe/Helsinki')),
                '2016-03-05 16:35:00',
            ],
            [
                new \DateTime('2016-06-18 18:35:00', new \DateTimeZone('Europe/Helsinki')),
                '2016-06-18 15:35:00',
            ],
            [
                new \DateTime('2016-06-18 18:35:00', new \DateTimeZone('UTC')),
                '2016-06-18 18:35:00',
            ],
        ];
    }
}
