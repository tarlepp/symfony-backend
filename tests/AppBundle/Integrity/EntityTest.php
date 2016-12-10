<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Integrity/EntityTest.php
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
class EntityTest extends KernelTestCase
{
    /**
     * @dataProvider dataProviderTestThatAllEntitiesHaveTestClass
     *
     * @param   string  $entity
     */
    public function testThatAllEntitiesHaveTestClass($entity)
    {
        $testFile = dirname(__FILE__) . '/../../' . str_replace(['App\\', '\\'], ['AppBundle\\', '/'], $entity) . 'Test.php';

        $message = sprintf(
            "Entity '%s' doesn't have required test class, please create it.",
            $entity
        );

        static::assertTrue(file_exists($testFile), $message);
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
            if (mb_substr($classMetaData->getName(), 0, 4) !== 'App\\') {
                return null;
            }

            return [$classMetaData->getName()];
        };

        return array_filter(array_map($filter, $classMetaData));
    }
}
