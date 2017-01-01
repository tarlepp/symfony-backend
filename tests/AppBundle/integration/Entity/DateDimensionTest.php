<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/DateDimensionTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\DateDimension;
use App\Tests\EntityTestCase;

/**
 * Class DateDimensionTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DateDimensionTest extends EntityTestCase
{
    /**
     * @var DateDimension
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = DateDimension::class;

    public function testThatConstructWithDateTimeObjectSetsDate()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals($dateTime, $entity->getDate(), 'getDate() method did not return expected value.');
    }

    public function testThatConstructWithDateTimeObjectSetsYear()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('Y'),
            $entity->getYear(),
            'getYear() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsMonth()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('n'),
            $entity->getMonth(),
            'getMonth() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsDay()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('j'),
            $entity->getDay(),
            'getDay() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsQuarter()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)floor(((int)$dateTime->format('n') - 1) / 3) + 1,
            $entity->getQuarter(),
            'getQuarter() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsWeekNumber()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('W'),
            $entity->getWeekNumber(),
            'getWeekNumber() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsDayNumberOfWeek()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('N'),
            $entity->getDayNumberOfWeek(),
            'getDayNumberOfWeek() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsDayNumberOfYear()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('z'),
            $entity->getDayNumberOfYear(),
            'getDayNumberOfYear() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsLeapYear()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('L'),
            $entity->isLeapYear(),
            'getLeapYear() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsWeekNumberingYear()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('o'),
            $entity->getWeekNumberingYear(),
            'getWeekNumberingYear() method did not return expected value.'
        );
    }

    public function testThatConstructWithDateTimeObjectSetsUnixTime()
    {
        $dateTime = new \DateTime();

        $entity = new DateDimension($dateTime);

        static::assertEquals(
            (int)$dateTime->format('U'),
            $entity->getUnixTime(),
            'getUnixTime() method did not return expected value.'
        );
    }
}
