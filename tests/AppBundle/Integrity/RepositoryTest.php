<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Integrity/RepositoryTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Integrity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class EntityTest
 *
 * @package AppBundle\Integrity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RepositoryTest extends KernelTestCase
{
    /**
     * @dataProvider dataProviderTestThatAllRepositoriesHaveTestClass
     *
     * @param   string  $repository
     */
    public function testThatAllRepositoriesHaveTestClass($repository)
    {
        $testFile = dirname(__FILE__) . '/../../' . str_replace(['App\\', '\\'], ['AppBundle\\', '/'], $repository) . 'Test.php';

        $message = sprintf(
            "Repository '%s' doesn't have required test class, please create it.",
            $repository
        );

        static::assertTrue(file_exists($testFile), $message);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatAllRepositoriesHaveTestClass()
    {
        self::bootKernel();

        /** @var EntityManager $em */
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $classMetaData = $em->getMetadataFactory()->getAllMetadata();

        // Filter out entity classes that aren't directly attached to Application itself
        $filter = function (ClassMetadata $classMetaData) use ($em) {
            if (mb_substr($classMetaData->getName(), 0, 4) !== 'App\\') {
                return null;
            }

            return [get_class($em->getRepository($classMetaData->getName()))];
        };

        return array_filter(array_map($filter, $classMetaData));
    }
}