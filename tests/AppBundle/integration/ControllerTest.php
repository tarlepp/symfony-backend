<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/ControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ControllerTest
 *
 * @package AppBundle\integration
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ControllerTest extends KernelTestCase
{
    /**
     * @dataProvider dataProviderTestThatAllControllersHaveTestClass
     *
     * @param   string  $expectedTestFile
     * @param   string  $controllerClass
     */
    public function testThatAllRestServicesHaveTestClass(string $expectedTestFile, string $controllerClass)
    {
        $message = sprintf(
            "Controller '%s' doesn't have required functional test class, please create it to '%s'.",
            $controllerClass,
            'tests/' . substr($expectedTestFile, mb_strpos($expectedTestFile, '/../') + 4)
        );

        static::assertFileExists($expectedTestFile, $message);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatAllControllersHaveTestClass(): array
    {
        $pattern = __DIR__ . '/../../../src/App/Controller/*.php';

        /**
         * @param   string  $filename
         *
         * @return  bool
         */
        $filter = function (string $filename): bool {
            $class = '\\App\\Controller\\' . str_replace('.php', '', basename($filename));

            $reflection = new \ReflectionClass($class);

            return !$reflection->isAbstract();
        };

        $basePath = __DIR__ . '/../functional/Controller/';

        /**
         * @param   string  $filename
         *
         * @return  array
         */
        $iterator = function (string $filename) use ($basePath): array {
            $class = '\\App\\Controller\\' . str_replace('.php', '', basename($filename));

            $reflection = new \ReflectionClass($class);

            return [
                $basePath . str_replace('.php', 'Test.php', basename($filename)),
                $reflection->getName(),
            ];
        };

        return array_map($iterator, array_filter(glob($pattern), $filter));
    }
}
