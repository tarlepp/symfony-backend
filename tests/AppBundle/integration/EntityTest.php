<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/EntityTest.php
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
class EntityTest extends KernelTestCase
{
    /**
     * @dataProvider dataProviderTestThatAllEntitiesHaveTestClass
     *
     * @param   string  $entity
     */
    public function testThatAllEntitiesHaveTestClass($entity)
    {
        $testFile = \str_replace(['App\\', '\\'], ['AppBundle\\integration\\', '/'], $entity);
        $testFile = static::$kernel->getRootDir() . '/../tests/' . $testFile . 'Test.php';
        $message = \sprintf(
            "Entity '%s' doesn't have required test class, please create it to '%s'.",
            $entity,
            $testFile
        );

        static::assertFileExists($testFile, $message);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatAllEntitiesHaveTestClass()
    {
        self::bootKernel();

        /** @var EntityManager $em */
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $classMetaData = $em->getMetadataFactory()->getAllMetadata();

        // Filter out entity classes that aren't directly attached to Application itself
        $filter = function (ClassMetadata $classMetaData) {
            if (0 !== mb_strpos($classMetaData->getName(), 'App\\')) {
                return null;
            }

            return [$classMetaData->getName()];
        };

        return \array_filter(\array_map($filter, $classMetaData));
    }
}
