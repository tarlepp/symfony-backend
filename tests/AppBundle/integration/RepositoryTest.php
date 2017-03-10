<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/RepositoryTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration;

use App\Tests\KernelTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class EntityTest
 *
 * @package AppBundle\integration
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
        $testFile = \str_replace(['App\\', '\\'], ['AppBundle\\functional\\', '/'], $repository);
        $testFile = static::$kernel->getRootDir() . '/../tests/' . $testFile . 'Test.php';

        $message = \sprintf(
            "Repository '%s' doesn't have required test class, please create it to '%s'.",
            $repository,
            $testFile
        );

        static::assertFileExists($testFile, $message);
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
            if (0 !== \mb_strpos($classMetaData->getName(), 'App\\')) {
                return null;
            }

            return [\get_class($em->getRepository($classMetaData->getName()))];
        };

        return \array_filter(\array_map($filter, $classMetaData));
    }
}
