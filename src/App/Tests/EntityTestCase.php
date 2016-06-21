<?php
/**
 * /src/App/Tests/EntityTestCase.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Entity\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class EntityTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class EntityTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        // Store container and entity manager
        $this->container = static::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        // Create new entity object
        $this->entity = new $this->entityName();

        $this->repository = $this->entityManager->getRepository($this->entityName);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks

        self::$kernel->shutdown();
    }

    /**
     * @dataProvider dataProviderTestThatSetterAndGettersWorks
     *
     * @param   string  $field
     */
    public function testThatGetterAndSetterExists($field)
    {
        $getter = 'get' . ucfirst($field);
        $setter = 'set' . ucfirst($field);

        $this->assertTrue(
            method_exists($this->entity, $getter),
            sprintf(
                "Entity '%s' does not have expected getter '%s()' method for '%s' property.",
                $this->entityName,
                $getter,
                $field
            )
        );

        $this->assertTrue(
            method_exists($this->entity, $setter),
            sprintf(
                "Entity '%s' does not have expected setter '%s()' method for '%s' property.",
                $this->entityName,
                $setter,
                $field
            )
        );
    }

    /**
     * @dataProvider dataProviderTestThatSetterAndGettersWorks
     *
     * @param   string  $field
     * @param   mixed   $value
     */
    public function testThatSetterReturnsInstanceOfEntity($field, $value)
    {
        $setter = 'set' . ucfirst($field);

        $this->assertInstanceOf(
            get_class($this->entity),
            call_user_func([$this->entity, $setter], $value),
            sprintf(
                "Entity '%s' setter '%s()' method for '%s' property did not return expected value.",
                $this->entityName,
                $setter,
                $field
            )
        );
    }

    /**
     * @dataProvider dataProviderTestThatSetterAndGettersWorks
     *
     * @param   string  $field
     * @param   string  $value
     * @param   string  $type
     */
    public function testThatGetterReturnsExpectedValue($field, $value, $type)
    {
        $getter = 'get' . ucfirst($field);
        $setter = 'set' . ucfirst($field);

        call_user_func([$this->entity, $setter], $value);

        $this->assertEquals($value, call_user_func([$this->entity, $getter]));

        try {
            if ($this->isType($type)) {
                $this->assertInternalType($type, call_user_func([$this->entity, $getter]));
            }
        } catch (\Exception $error) {
            $this->assertInstanceOf($type, call_user_func([$this->entity, $getter]));
        }
    }

    /**
     * @dataProvider dataProviderTestThatAssociationMethodsExists
     *
     * @param   string          $method
     * @param   string          $field
     * @param   mixed           $input
     * @param   boolean|string  $expectedOutput
     */
    public function testThatAssociationMethodsExistsAndThoseReturnsCorrectValue(
        $method,
        $field,
        $input,
        $expectedOutput
    ) {
        $this->assertTrue(
            method_exists($this->entity, $method),
            sprintf(
                "Entity '%s' does not have expected association method '%s()' for property '%s'.",
                $this->entityName,
                $method,
                $field
            )
        );

        if ($expectedOutput) {
            $this->assertInstanceOf($expectedOutput, call_user_func([$this->entity, $method], $input));
        }
    }

    /**
     * @dataProvider dataProviderTestThatManyToManyAssociationMethodsWorksAsExpected
     *
     */
    public function testThatManyToManyAssociationMethodsWorksAsExpected(
        $methodGetter,
        $methodAdder,
        $methodRemoval,
        $field,
        $targetEntity,
        $mappings
    ) {
        if ($methodGetter === false) {
            $this->markTestSkipped('Entity does not contain many-to-many relationships.');
        }

        $this->assertInstanceOf(
            get_class($this->entity),
            call_user_func([$this->entity, $methodAdder], $targetEntity),
            sprintf(
                "Added method '%s()' for property '%s' did not return instance of the entity itself",
                $methodAdder,
                $field
            )
        );

        /** @var ArrayCollection $collection */
        $collection = call_user_func([$this->entity, $methodGetter]);

        $this->assertTrue(
            $collection->contains($targetEntity)
        );

        if (isset($mappings['mappedBy'])) {
            /** @var ArrayCollection $collection */
            $collection = call_user_func([$targetEntity, 'get' . ucfirst($mappings['mappedBy'])]);

            $this->assertTrue($collection->contains($this->entity));
        } elseif (isset($mappings['inversedBy'])) {
            /** @var ArrayCollection $collection */
            $collection = call_user_func([$targetEntity, 'get' . ucfirst($mappings['inversedBy'])]);

            $this->assertTrue($collection->contains($this->entity));
        }

        $this->assertInstanceOf(
            get_class($this->entity),
            call_user_func([$this->entity, $methodRemoval], $targetEntity),
            sprintf(
                "REmoval method '%s()' for property '%s' did not return instance of the entity itself",
                $methodAdder,
                $field
            )
        );

        /** @var ArrayCollection $collection */
        $collection = call_user_func([$this->entity, $methodGetter]);

        $this->assertTrue($collection->isEmpty());

        if (isset($mappings['mappedBy'])) {
            /** @var ArrayCollection $collection */
            $collection = call_user_func([$targetEntity, 'get' . ucfirst($mappings['mappedBy'])]);

            $this->assertTrue($collection->isEmpty());
        } elseif (isset($mappings['inversedBy'])) {
            /** @var ArrayCollection $collection */
            $collection = call_user_func([$targetEntity, 'get' . ucfirst($mappings['inversedBy'])]);

            $this->assertTrue($collection->isEmpty());
        }
    }

    /**
     * Generic data provider for following common entity tests:
     *  - testThatGetterAndSetterExists
     *  - testThatSetterReturnsInstanceOfEntity
     *  - testThatGetterReturnsExpectedValue
     *
     * @return array
     */
    public function dataProviderTestThatSetterAndGettersWorks()
    {
        self::bootKernel();

        // Get entity manager
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        // Get entity class meta data
        $meta = $entityManager->getClassMetadata($this->entityName);

        /**
         * Lambda function to generate actual test case arrays for tests. Output value is an array which contains
         * following data:
         *  1) Name
         *  2) Value
         *  3) Type
         *
         * @param   string  $field
         *
         * @return  array
         */
        $iterator = function ($field) use ($meta) {
            $type = $meta->getTypeOfField($field);

            switch ($type) {
                case 'integer':
                    $value = 666;
                    break;
                case 'datetime':
                    $value = new \DateTime();
                    $type = '\DateTime';
                    break;
                case 'text':
                case 'string':
                default:
                    $value = 'Some text here';
                    $type = 'string';
                    break;
            }

            return [$field, $value, $type];
        };

        $fieldsToOmit = array_merge(
            $meta->getIdentifierFieldNames(),
            ['password']
        );

        /**
         * Lambda function to filter out all fields that cannot be tested generic
         *
         * @param   string  $field
         *
         * @return  bool
         */
        $filter = function ($field) use ($fieldsToOmit) {
            return !in_array($field, $fieldsToOmit);
        };

        $entityManager->close();
        $entityManager = null; // avoid memory leaks

        self::$kernel->shutdown();

        return array_map($iterator, array_filter($meta->getFieldNames(), $filter));
    }

    public function dataProviderTestThatManyToManyAssociationMethodsWorksAsExpected()
    {
        self::bootKernel();

        // Get entity manager
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        // Get entity class meta data
        $meta = $entityManager->getClassMetadata($this->entityName);

        $iterator = function ($mapping) {
            $targetEntity = new $mapping['targetEntity']();

            $singular = mb_substr($mapping['fieldName'], -1, 1) === 's' ?
                mb_substr($mapping['fieldName'], 0, -1) : $mapping['fieldName'];

            return [
                [
                    'get' . ucfirst($mapping['fieldName']),
                    'add' . ucfirst($singular),
                    'remove' . ucfirst($singular),
                    $mapping['fieldName'],
                    $targetEntity,
                    $mapping,
                ]
            ];
        };

        $filter = function ($mapping) {
            return $mapping['type'] === ClassMetadataInfo::MANY_TO_MANY;
        };

        $entityManager->close();
        $entityManager = null; // avoid memory leaks

        self::$kernel->shutdown();

        $items = array_filter($meta->getAssociationMappings(), $filter);

        if (empty($items)) {
            return [
                [false, false, false, false, false, false]
            ];
        }

        return call_user_func_array('array_merge', array_map($iterator, $items));
    }

    public function dataProviderTestThatAssociationMethodsExists()
    {
        self::bootKernel();

        // Get entity manager
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        // Get entity class meta data
        $meta = $entityManager->getClassMetadata($this->entityName);

        $iterator = function ($mapping) {
            $input = new $mapping['targetEntity']();

            $methods = [
                ['get' . ucfirst($mapping['fieldName']), $mapping['fieldName'], false, false]
            ];

            switch ($mapping['type']) {
                case ClassMetadataInfo::ONE_TO_ONE:
                    break;
                case ClassMetadataInfo::MANY_TO_ONE:
                    $methods[] = [
                        'set' . ucfirst($mapping['fieldName']),
                        $mapping['fieldName'],
                        $input,
                        $this->entityName
                    ];
                    break;
                case ClassMetadataInfo::ONE_TO_MANY:
                    break;
                case ClassMetadataInfo::MANY_TO_MANY:
                    $singular = mb_substr($mapping['fieldName'], -1, 1) === 's' ?
                        mb_substr($mapping['fieldName'], 0, -1) : $mapping['fieldName'];

                    $methods = [
                        [
                            'get' . ucfirst($mapping['fieldName']),
                            $mapping['fieldName'],
                            $input,
                            'Doctrine\Common\Collections\ArrayCollection'
                        ],
                        [
                            'add' . ucfirst($singular),
                            $mapping['fieldName'],
                            $input,
                            $this->entityName
                        ],
                        [
                            'remove' . ucfirst($singular),
                            $mapping['fieldName'],
                            $input,
                            $this->entityName
                        ],
                    ];
                    break;
            }

            return $methods;
        };

        $entityManager->close();
        $entityManager = null; // avoid memory leaks

        self::$kernel->shutdown();

        return call_user_func_array('array_merge', array_map($iterator, $meta->getAssociationMappings()));
    }
}
