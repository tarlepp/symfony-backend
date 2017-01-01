<?php
/**
 * /src/App/Tests/RestServiceTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Services\Rest\Base;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class RestServiceTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class RestServiceTestCase extends ContainerTestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Base
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $repositoryName;

    /**
     * How many entities should be found with 'find()' method without parameters. Set to false if you want to skip
     * "proper" checks - otherwise marks test as incomplete.
     *
     * @var int|boolean
     */
    protected $entityCount = 0;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        // Store container and entity manager
        $this->container = static::$kernel->getContainer();
        $this->service = $this->container->get($this->serviceName);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        self::$kernel->shutdown();
    }

    /**
     * Method to test that service returns expected entity name.
     */
    public function testThatGetEntityNameReturnsExpectedValue()
    {
        if (null === $this->entityName) {
            static::fail('You have not specified entityName to your test class.');
        }

        $message = sprintf(
            "Rest service '%s->getEntityName()' did not return expected entity name '%s'.",
            $this->serviceName,
            $this->entityName
        );

        static::assertEquals($this->entityName, $this->service->getEntityName(), $message);
    }

    /**
     * Method to test that getReference method returns expected value.
     */
    public function testThatGetReferenceReturnsExpectedProxy()
    {
        if (null === $this->entityName) {
            static::fail('You have not specified entityName to your test class.');
        }

        $proxy = 'Proxies\\__CG__\\' . $this->entityName;

        $message = sprintf(
            "Rest service '%s->getReference()' did not return expected proxy '%s' instance.",
            $this->serviceName,
            $proxy
        );

        static::assertInstanceOf($proxy, $this->service->getReference('testReference'), $message);
    }

    /**
     * Method to test that service returns expected repository.
     */
    public function testThatGetRepositoryReturnsExpectedValue()
    {
        if (null === $this->repositoryName) {
            static::fail('You have not specified repositoryName to your test class.');
        }

        $message = sprintf(
            "Rest service '%s->getRepository()' did not return expected repository '%s' instance.",
            $this->serviceName,
            $this->repositoryName
        );

        static::assertInstanceOf($this->repositoryName, $this->service->getRepository(), $message);
    }

    /**
     * Method to test that service returns an array when calling getAssociations method.
     */
    public function testThatGetAssociationsReturnsAnArray()
    {
        static::assertInternalType('array', $this->service->getAssociations());
    }

    /**
     * Method to test that 'find' method returns an array of entity objects.
     */
    public function testThatFindMethodReturnsAnArray()
    {
        $data = $this->service->find();

        static::assertInternalType('array', $data);

        // Skip proper checks of entity
        if ($this->entityCount === false) {
            return;
        } elseif ($this->entityCount > 0) {
            $message = sprintf(
                "Rest service '%s->find()' did not return expected count of entities.",
                $this->serviceName
            );

            static::assertCount($this->entityCount, $data, $message);

            $message = sprintf(
                "Rest service '%s->find()' did not return an array of '%s' entities.",
                $this->serviceName,
                $this->entityName
            );

            static::assertInstanceOf($this->entityName, $data[0], $message);
        } else {
            $message = sprintf(
                "Cannot test service '%s->find()' method properly because test class doesn't have 'entityCount'.",
                $this->serviceName
            );

            static::markTestIncomplete($message);
        }
    }

    /**
     * Method to test that 'findOne' method throws a HttpException with invalid id.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatFindOneThrowsAnException()
    {
        $this->service->findOne('this id does not exists', true);
    }

    /**
     * Method to test that 'findOneBy' method throws a HttpException with invalid id.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage Not found
     */
    public function testThatFindOneByThrowsAnException()
    {
        $this->service->findOneBy(['id' => 'this id does not exists'], [], true);
    }
}
