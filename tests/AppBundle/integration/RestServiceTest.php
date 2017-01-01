<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/RestServiceTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration;

/**
 * Class RestServiceTest
 *
 * @package AppBundle\integration
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RestServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderTestThatAllRestServicesHaveTestClass
     *
     * @param   string  $expectedTestFile
     * @param   string  $serviceClass
     */
    public function testThatAllRestServicesHaveTestClass(string $expectedTestFile, string $serviceClass)
    {
        $message = sprintf(
            "Rest service '%s' doesn't have required functional test class, please create it to '%s'.",
            $serviceClass,
            'tests/' . substr($expectedTestFile, mb_strpos($expectedTestFile, '/../') + 4)
        );

        static::assertFileExists($expectedTestFile, $message);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatAllRestServicesHaveTestClass(): array
    {
        $pattern = __DIR__ . '/../../../src/App/Services/Rest/*.php';

        /**
         * @param   string  $filename
         *
         * @return  bool
         */
        $filter = function (string $filename): bool {
            $class = '\\App\\Services\\Rest\\' . str_replace('.php', '', basename($filename));

            $reflection = new \ReflectionClass($class);

            return !$reflection->isAbstract();
        };

        $basePath = __DIR__ . '/../functional/Services/Rest/';

        /**
         * @param   string  $filename
         *
         * @return  array
         */
        $iterator = function (string $filename) use ($basePath): array {
            $class = '\\App\\Services\\Rest\\' . str_replace('.php', '', basename($filename));

            $reflection = new \ReflectionClass($class);

            return [
                $basePath . str_replace('.php', 'Test.php', basename($filename)),
                $reflection->getName(),
            ];
        };

        return array_map($iterator, array_filter(glob($pattern), $filter));
    }
}
