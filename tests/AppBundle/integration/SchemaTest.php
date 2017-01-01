<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/SchemaTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration;

use Doctrine\ORM\Tools\SchemaValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class SchemaTest
 *
 * @package AppBundle\Database
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SchemaTest extends KernelTestCase
{
    /**
     * @var SchemaValidator
     */
    private $validator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->validator = new SchemaValidator($em);
    }

    public function testThatMappingsAreValid()
    {
        $errors = $this->validator->validateMapping();

        $messages = [];

        $formatter = function ($errors, $className) use (&$messages) {
            $messages[] = $className . ': ' . implode(', ', $errors);
        };

        array_walk($errors, $formatter);

        static::assertEmpty($errors, implode("\n", $messages));
    }

    public function testThatSchemaInSyncWithMetadata()
    {
        static::assertTrue(
            $this->validator->schemaInSyncWithMetadata(),
            'The database schema is not in sync with the current mapping file.'
        );
    }
}
