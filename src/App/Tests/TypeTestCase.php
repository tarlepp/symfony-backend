<?php
declare(strict_types = 1);
/**
 * /src/App/Tests/TypeTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TypeTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class TypeTestCase extends FormIntegrationTestCase
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * @param \DateTime $expected
     * @param \DateTime $actual
     */
    public static function assertDateTimeEquals(\DateTime $expected, \DateTime $actual)
    {
        self::assertSame($expected->format('c'), $actual->format('c'));
    }

    /**
     * @param \DateInterval $expected
     * @param \DateInterval $actual
     */
    public static function assertDateIntervalEquals(\DateInterval $expected, \DateInterval $actual)
    {
        self::assertSame($expected->format('%RP%yY%mM%dDT%hH%iM%sS'), $actual->format('%RP%yY%mM%dDT%hH%iM%sS'));
    }
}
