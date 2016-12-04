<?php
declare(strict_types = 1);
/**
 * /spec/App/Doctrine/DBAL/Types/UTCDateTimeTypeSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\UTCDateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PhpSpec\ObjectBehavior;

/**
 * Class UTCDateTimeTypeSpec
 *
 * @mixin UTCDateTimeType
 *
 * @package spec\App\Doctrine\DBAL\Types
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UTCDateTimeTypeSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|AbstractPlatform  $platform
     */
    function let(AbstractPlatform $platform)
    {
        if (!UTCDateTimeType::hasType('dateTypeSpec')) {
            UTCDateTimeType::addType('dateTypeSpec', UTCDateTimeType::class);
        }

        $platform->getDateTimeFormatString()->willReturn('Y-m-d H:i:s');

        $this->beConstructedThrough('getType', ['dateTypeSpec']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UTCDateTimeType::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|AbstractPlatform  $platform
     */
    function it_should_return_expected_value_when_calling_convertToDatabaseValue(AbstractPlatform $platform)
    {
        // Create value to convert
        $value = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2016-12-04 21:00:00',
            new \DateTimeZone('Europe/Helsinki')
        );

        /** @var \PhpSpec\Wrapper\Subject $converted */
        $converted = $this->convertToDatabaseValue($value, $platform);

        $converted->shouldBeString();
        $converted->shouldReturn('2016-12-04 19:00:00');
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|AbstractPlatform  $platform
     */
    function it_should_return_expected_value_when_calling_convertToPHPValue(AbstractPlatform $platform)
    {
        $value = '2016-12-04 19:00:00';

        /** @var \PhpSpec\Wrapper\Subject|\DateTime $converted */
        $converted = $this->convertToPHPValue($value, $platform);

        $converted->shouldBeAnInstanceOf(\DateTime::class);
        $converted->format('Y-m-d H:i:s')->shouldReturn($value);

        /** @var \PhpSpec\Wrapper\Subject|\DateTimeZone $timeZone */
        $timeZone = $converted->getTimezone();

        $timeZone->shouldBeAnInstanceOf(\DateTimeZone::class);
        $timeZone->getName()->shouldReturn('UTC');
    }
}
