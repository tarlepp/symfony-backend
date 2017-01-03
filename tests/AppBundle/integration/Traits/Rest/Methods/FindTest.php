<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Traits/Rest/Methods/FindTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Traits\Rest\Methods;

use App\Traits\Rest\Methods\Find;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\integration\Traits\Rest\Methods\Find as FindTestClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

require_once __DIR__ . '/Find.php';

/**
 * Class FindTest
 *
 * @package AppBundle\integration\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class FindTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\Find trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $mock = $this->getMockForTrait(Find::class);
        $request = Request::create('/', 'GET');

        $mock->findMethod($request);
    }

    /**
     * @dataProvider dataProviderTestThatTraitHandlesException
     *
     * @param   \Exception  $exception
     * @param   int         $expectedCode
     */
    public function testThatTraitHandlesException(\Exception $exception, int $expectedCode)
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var FindTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(
            FindTestClass::class,
            [$resourceService, $restHelperResponse]
        );

        $request = Request::create('/', 'GET');

        $resourceService
            ->expects(static::once())
            ->method('find')
            ->willThrowException($exception);

        $testClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $this->expectException(HttpException::class);
        $this->expectExceptionCode($expectedCode);

        $testClass->findMethod($request);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var FindTestClass|\PHPUnit_Framework_MockObject_MockObject $findTestClass */
        $findTestClass = $this->getMockForAbstractClass(FindTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/', 'GET');
        $response = Response::create('[]');

        $resourceService
            ->expects(static::once())
            ->method('find')
            ->withAnyParameters()
            ->willReturn([]);

        $restHelperResponse
            ->expects(static::once())
            ->method('createResponse')
            ->withAnyParameters()
            ->willReturn($response);

        $findTestClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $findTestClass
            ->expects(static::once())
            ->method('getResponseService')
            ->willReturn($restHelperResponse);

        $findTestClass->findMethod($request);
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

        /** @var FindTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(FindTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/count', $httpMethod);

        $testClass->findMethod($request)->getContent();
    }

    public function testThatTraitCallsProcessCriteriaIfItExists()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var FindTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(
            FindTestClass::class,
            [$resourceService, $restHelperResponse],
            '',
            true,
            true,
            true,
            ['processCriteria']
        );

        // Create request
        $request = Request::create('/count', 'GET');

        $testClass
            ->expects(static::once())
            ->method('processCriteria')
            ->withAnyParameters()
        ;

        $testClass->findMethod($request)->getContent();
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitThrowsAnExceptionWithWrongHttpMethod(): array
    {
        return [
            ['HEAD'],
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitHandlesException(): array
    {
        return [
            [new HttpException(400), 0],
            [new \LogicException(), 400],
            [new \InvalidArgumentException(), 400],
            [new \Exception(), 400],
        ];
    }
}
