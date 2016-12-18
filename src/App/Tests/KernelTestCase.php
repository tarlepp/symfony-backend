<?php
declare(strict_types=1);
/**
 * /src/App/Tests/KernelTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as SymfonyKernelTestCase;

/**
 * Class KernelTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class KernelTestCase extends SymfonyKernelTestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param   object  $object     Instantiated object that we will run method on.
     * @param   string  $methodName Method name to call
     * @param   array   $parameters Array of parameters to pass into method.
     *
     * @return  mixed
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
