<?php
declare(strict_types = 1);
/**
 * /src/App/Tests/KernelTestCase.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class KernelTestCase
 *
 * Basically just the same as symfony KernelTestCase , we need this because we're using phpunit 6.x branch atm and
 * symfony does not support that correctly atm.
 *
 * @link https://github.com/symfony/symfony/issues/21534
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class KernelTestCase extends TestCase
{
    protected static $class;

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    /**
     * Finds the directory where the phpunit.xml(.dist) is stored.
     *
     * If you run tests with the PHPUnit CLI tool, everything will work as expected.
     * If not, override this method in your test classes.
     *
     * @return string The directory where phpunit.xml(.dist) is stored
     *
     * @throws \RuntimeException
     */
    protected static function getPhpUnitXmlDir()
    {
        if (!isset($_SERVER['argv']) || false === \strpos($_SERVER['argv'][0], 'phpunit')) {
            throw new \RuntimeException('You must override the KernelTestCase::createKernel() method.');
        }

        $dir = static::getPhpUnitCliConfigArgument();

        if ($dir === null &&
            (\is_file(\getcwd() . \DIRECTORY_SEPARATOR . 'phpunit.xml') ||
                \is_file(\getcwd() . \DIRECTORY_SEPARATOR . 'phpunit.xml.dist'))) {
            $dir = \getcwd();
        }

        // Can't continue
        if ($dir === null) {
            throw new \RuntimeException('Unable to guess the Kernel directory.');
        }

        if (!\is_dir($dir)) {
            $dir = \dirname($dir);
        }

        return $dir;
    }

    /**
     * Finds the value of the CLI configuration option.
     *
     * PHPUnit will use the last configuration argument on the command line, so this only returns
     * the last configuration argument.
     *
     * @return string The value of the PHPUnit CLI configuration option
     */
    private static function getPhpUnitCliConfigArgument()
    {
        $dir = null;
        $reversedArgs = \array_reverse($_SERVER['argv']);

        foreach ($reversedArgs as $argIndex => $testArg) {
            if ($testArg === '--configuration' || \preg_match('/^-[^ \-]*c$/', $testArg)) {
                $dir = \realpath($reversedArgs[$argIndex - 1]);
                break;
            }

            if (\strpos($testArg, '--configuration=') === 0) {
                $argPath = \substr($testArg, \strlen('--configuration='));
                $dir = \realpath($argPath);
                break;
            }

            if (\strpos($testArg, '-c') === 0) {
                $argPath = \substr($testArg, \strlen('-c'));
                $dir = \realpath($argPath);
                break;
            }
        }

        return $dir;
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @return string The Kernel class name
     *
     * @throws \RuntimeException
     */
    protected static function getKernelClass()
    {
        if (isset($_SERVER['KERNEL_DIR'])) {
            $dir = $_SERVER['KERNEL_DIR'];

            if (!\is_dir($dir)) {
                $phpUnitDir = static::getPhpUnitXmlDir();
                if (\is_dir("$phpUnitDir/$dir")) {
                    $dir = "$phpUnitDir/$dir";
                }
            }
        } else {
            $dir = static::getPhpUnitXmlDir();
        }

        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($dir);
        $results = \iterator_to_array($finder);

        if (!\count($results)) {
            throw new \RuntimeException('Either set KERNEL_DIR in your phpunit.xml according to https://symfony.com/doc/current/book/testing.html#your-first-functional-test or override the WebTestCase::createKernel() method.');
        }

        $file = \current($results);
        $class = $file->getBasename('.php');

        /** @noinspection PhpIncludeInspection */
        require_once $file;

        return $class;
    }

    /**
     * Boots the Kernel for this test.
     *
     * @param array $options
     */
    protected static function bootKernel(array $options = null)
    {
        $options = $options ?? [];

        static::ensureKernelShutdown();

        static::$kernel = static::createKernel($options);
        static::$kernel->boot();
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = null)
    {
        $options = $options ?? [];

        if (static::$class === null) {
            static::$class = static::getKernelClass();
        }

        return new static::$class(
            $options['environment'] ?? 'test',
            $options['debug'] ?? true
        );
    }

    /**
     * Shuts the kernel down if it was used in the test.
     */
    protected static function ensureKernelShutdown()
    {
        if (static::$kernel !== null) {
            $container = static::$kernel->getContainer();

            static::$kernel->shutdown();

            if ($container instanceof ResettableContainerInterface) {
                $container->reset();
            }
        }
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        static::ensureKernelShutdown();
    }
}
