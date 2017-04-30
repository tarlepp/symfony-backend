<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/DTO/Rest/GenericRestDtoTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\DTO\Rest;

use App\DTO\Rest\Interfaces\RestDto;
use App\Tests\KernelTestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\TypeParser;

/**
 * Class GenericRestDtoTest
 *
 * @package AppBundle\integration\DTO\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class GenericRestDtoTest extends KernelTestCase
{
    /**
     * @var TypeParser
     */
    private $parser;

    /**
     * @param   string  $folder
     * @param   string  $pattern
     *
     * @return  array
     */
    public static function recursiveFileSearch(string $folder, string $pattern): array
    {
        $dir = new \RecursiveDirectoryIterator($folder);
        $ite = new \RecursiveIteratorIterator($dir);

        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = array();

        foreach ($files as $file) {
            $fileList[] = $file[0];
        }

        return $fileList;
    }

    /**
     * @dataProvider dataProviderRestDto
     *
     * @param   \ReflectionClass    $dtoReflection
     * @param   string              $dtoClass
     */
    public function testThatPropertiesHaveGetters(\ReflectionClass $dtoReflection, string $dtoClass)
    {
        foreach ($dtoReflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getDeclaringClass()->getName() !== $dtoClass) {
                continue;
            }

            $method = 'get' . \ucfirst($reflectionProperty->getName());

            $message = \sprintf(
                "REST DTO class '%s' does not have required getter method '%s' for property '%s'.",
                $dtoClass,
                $method,
                $reflectionProperty->getName()
            );

            self::assertTrue($dtoReflection->hasMethod($method), $message);
        }
    }

    /**
     * @dataProvider dataProviderRestDto
     *
     * @param   \ReflectionClass    $dtoReflection
     * @param   string              $dtoClass
     */
    public function testThatPropertiesHaveSetters(\ReflectionClass $dtoReflection, string $dtoClass)
    {
        foreach ($dtoReflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getDeclaringClass()->getName() !== $dtoClass) {
                continue;
            }

            $method = 'set' . \ucfirst($reflectionProperty->getName());

            $message = \sprintf(
                "REST DTO class '%s' does not have required setter method '%s' for property '%s'.",
                $dtoClass,
                $method,
                $reflectionProperty->getName()
            );

            self::assertTrue($dtoReflection->hasMethod($method), $message);
        }
    }

    /**
     * @dataProvider dataProviderRestDto
     *
     * @param   \ReflectionClass    $dtoReflection
     * @param   string              $dtoClass
     */
    public function testThatSetterCallsSetVisitedMethod(\ReflectionClass $dtoReflection, string $dtoClass)
    {
        $filter = function (\ReflectionProperty $reflectionProperty) use ($dtoClass) {
            return $reflectionProperty->getDeclaringClass()->getName() === $dtoClass;
        };

        $properties = \array_filter($dtoReflection->getProperties(), $filter);

        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|RestDto $mock
         */
        $mock = $this->getMockBuilder($dtoClass)
            ->setMethods(['setVisited'])
            ->getMock();

        $mock->expects(static::exactly(\count($properties)))
            ->method('setVisited');

        $annotationReader = new AnnotationReader();

        $expectedVisited = [];

        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($properties as $reflectionProperty) {
            // Get "valid" value for current property
            $value = $this->getValueForProperty($dtoReflection, $reflectionProperty, $annotationReader);

            // Determine setter method for current property
            $setter = 'set' . \ucfirst($reflectionProperty->getName());

            // Call setter method
            $mock->$setter($value);
        }

        self::assertEquals($expectedVisited, $mock->getVisited());
    }

    /**
     * @dataProvider dataProviderRestDto
     *
     * @param   \ReflectionClass    $dtoReflection
     * @param   string              $dtoClass
     */
    public function testThatGetVisitedReturnsExpected(\ReflectionClass $dtoReflection, string $dtoClass)
    {
        $filter = function (\ReflectionProperty $reflectionProperty) use ($dtoClass) {
            return $reflectionProperty->getDeclaringClass()->getName() === $dtoClass;
        };

        $properties = \array_filter($dtoReflection->getProperties(), $filter);

        $iterator = function (\ReflectionProperty $property) {
            return $property->getName();
        };

        $expectedVisited = \array_map($iterator, $properties);

        $annotationReader = new AnnotationReader();

        /**
         * @var RestDto $dto
         */
        $dto = new $dtoClass();

        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($properties as $reflectionProperty) {
            // Get "valid" value for current property
            $value = $this->getValueForProperty($dtoReflection, $reflectionProperty, $annotationReader);

            // Determine setter method for current property
            $setter = 'set' . \ucfirst($reflectionProperty->getName());

            // Call setter method
            $dto->$setter($value);
        }

        self::assertEquals($expectedVisited, $dto->getVisited());
    }

    /**
     * @return array
     */
    public function dataProviderRestDto(): array
    {
        self::bootKernel();

        $folder = static::$kernel->getRootDir() . '/../src/App/DTO/Rest/';
        $pattern = '/^.+\.php$/i';

        $replacement = static::$kernel->getRootDir() . '/../src';

        $iteratorClass = function (string $filename) use ($replacement) {
            $class = \str_replace([$replacement, '.php', '/'], ['', '', '\\'], $filename);

            return new \ReflectionClass($class);
        };


        $filterClass = function (\ReflectionClass $data) {
            return !($data->isAbstract() || $data->isInterface());
        };

        $iteratorFormat = function (\ReflectionClass $data) {
            return [
                $data,
                $data->getName(),
            ];
        };

        $classes = \array_map(
            $iteratorFormat,
            \array_filter(
                \array_map(
                    $iteratorClass,
                    self::recursiveFileSearch($folder, $pattern)
                ),
                $filterClass
            )
        );

        return $classes;
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new TypeParser();
    }

    /**
     * Method to get "valid" value for DTO property.
     *
     * @param   \ReflectionClass    $dtoReflection
     * @param   \ReflectionProperty $reflectionProperty
     * @param   AnnotationReader    $annotationReader
     *
     * @return  float|int|string|mixed
     */
    private function getValueForProperty(
        \ReflectionClass $dtoReflection,
        \ReflectionProperty $reflectionProperty,
        AnnotationReader $annotationReader
    ) {
        $type = $annotationReader->getPropertyAnnotation($reflectionProperty, Type::class);

        if ($type === null) {
            $message = \sprintf(
                "DTO class '%s' property '%s' does not have required 'JMS\Serializer\Annotation\Type' annotation",
                $dtoReflection->getName(),
                $reflectionProperty->getName()
            );

            throw new \DomainException($message);
        }

        $typeName = $this->parser->parse($type->name)['name'];

        switch ($typeName) {
            case 'string';
                $output = 'foobar';
                break;
            case 'int':
            case 'integer':
                $output = 123;
                break;
            case 'float':
            case 'double':
            case 'decimal':
                $output = 0.123;
                break;
            default:
                $className = $typeName[0] === '\\' ? $typeName : '\\' . $typeName;

                $output = new $className();
                break;
        }

        return $output;
    }
}