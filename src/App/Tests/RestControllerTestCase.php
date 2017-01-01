<?php
declare(strict_types=1);
/**
 * /src/App/Tests/RestControllerTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RestControllerTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RestControllerTestCase extends KernelTestCase
{
    /**
     * @var string
     */
    protected static $controllerName;

    /**
     * @var string
     */
    protected static $resourceServiceName;

    /**
     * @var string
     */
    protected static $repositoryName;

    /**
     * @var RestController
     */
    protected $controller;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $mockResourceService = $this->createMock(static::$resourceServiceName);
        $mockRepository = $this->createMock(static::$repositoryName);
        $mockRestHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        $mockResourceService
            ->expects(static::any())
            ->method('getRepository')
            ->willReturn($mockRepository);

        $this->controller = new static::$controllerName($mockResourceService, $mockRestHelperResponse);
    }

    public function testThatResourceServiceReturnsExpectedService()
    {
        static::assertInstanceOf(static::$resourceServiceName, $this->controller->getResourceService());
    }

    public function testThatResourceServiceReturnsExpectedRepository()
    {
        static::assertInstanceOf(
            static::$repositoryName,
            $this->controller->getResourceService()->getRepository()
        );
    }
}
