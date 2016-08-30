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
        if (is_null($this->entityName)) {
            $this->fail('You have not specified entityName to your test class.');
        }

        $message = sprintf(
            "Rest service '%s->getEntityName()' did not return expected entity name '%s'.",
            $this->serviceName,
            $this->entityName
        );

        $this->assertEquals($this->entityName, $this->service->getEntityName(), $message);
    }

    /**
     * Method to test that getReference method returns expected value.
     */
    public function testThatGetReferenceReturnsExpectedProxy()
    {
        if (is_null($this->entityName)) {
            $this->fail('You have not specified entityName to your test class.');
        }

        $proxy = 'Proxies\\__CG__\\' . $this->entityName;

        $message = sprintf(
            "Rest service '%s->getReference()' did not return expected proxy '%s' instance.",
            $this->serviceName,
            $proxy
        );

        $this->assertTrue($this->service->getReference('testReference') instanceof $proxy, $message);
    }

    /**
     * Method to test that service returns expected repository.
     */
    public function testThatGetRepositoryReturnsExpectedValue()
    {
        if (is_null($this->repositoryName)) {
            $this->fail('You have not specified repositoryName to your test class.');
        }

        $message = sprintf(
            "Rest service '%s->getRepository()' did not return expected repository '%s' instance.",
            $this->serviceName,
            $this->repositoryName
        );

        $this->assertTrue($this->service->getRepository() instanceof $this->repositoryName, $message);
    }

    /**
     * Method to test that service returns an array when calling getAssociations method.
     */
    public function testThatGetAssociationsReturnsAnArray()
    {
        $this->assertInternalType('array', $this->service->getAssociations());
    }
}
