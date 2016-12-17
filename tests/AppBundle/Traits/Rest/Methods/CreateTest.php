<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/CreateTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Entity\Interfaces\EntityInterface;
use App\Traits\Rest\Methods\Create;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\Traits\Rest\Methods\Create as CreateTestClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__FILE__) . '/Create.php';

/**
 * Class CreateTest
 *
 * @package AppBundle\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CreateTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\Create trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $mock = $this->getMockForTrait(Create::class);
        $request = Request::create('/', 'POST');

        $mock->createMethod($request);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $entityInterface = $this->createMock(EntityInterface::class);

        /** @var CreateTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(CreateTestClass::class, [$resourceService, $restHelperResponse]);

        $request
            ->expects(static::once())
            ->method('getContent')
            ->willReturn('{"foo":"bar"}');

        $request
            ->expects(static::once())
            ->method('getMethod')
            ->willReturn('POST');

        $resourceService
            ->expects(static::once())
            ->method('create')
            ->withAnyParameters()
            ->willReturn($entityInterface);

        $restHelperResponse
            ->expects(static::once())
            ->method('createResponse')
            ->withAnyParameters()
            ->willReturn($response);

        $testClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $testClass
            ->expects(static::once())
            ->method('getResponseService')
            ->willReturn($restHelperResponse);

        $testClass->createMethod($request)->getContent();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @dataProvider dataProviderTestThatTraitThrowsAnExceptionWithWrongHttpMethod
     *
     * @param   string  $httpMethod
     */
    public function testThatTraitThrowsAnExceptionWithWrongHttpMethod(string $httpMethod)
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var CreateTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(CreateTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/', $httpMethod);

        $testClass->createMethod($request)->getContent();
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitThrowsAnExceptionWithWrongHttpMethod(): array
    {
        return [
            ['HEAD'],
            ['GET'],
            ['PUT'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];
    }
}