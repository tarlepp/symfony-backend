<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/Traits/Rest/Methods/CountTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\Traits\Rest\Methods;

use App\Traits\Rest\Methods\Count;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\unit\Traits\Rest\Methods\Count as CountTestClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__FILE__) . '/Count.php';

/**
 * Class CountTest
 *
 * @package AppBundle\unit\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class CountTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\Count trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $mock = $this->getMockForTrait(Count::class);
        $request = Request::create('/count', 'GET');

        $mock->countMethod($request);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var CountTestClass|\PHPUnit_Framework_MockObject_MockObject $countTestClass */
        $countTestClass = $this->getMockForAbstractClass(CountTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/count', 'GET');
        $response = Response::create(123);

        $resourceService
            ->expects(static::once())
            ->method('count')
            ->withAnyParameters()
            ->willReturn(123);

        $restHelperResponse
            ->expects(static::once())
            ->method('createResponse')
            ->withAnyParameters()
            ->willReturn($response);

        $countTestClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $countTestClass
            ->expects(static::once())
            ->method('getResponseService')
            ->willReturn($restHelperResponse);

        $countTestClass->countMethod($request)->getContent();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @dataProvider dataProviderTestThatTraitsThrowsAnExceptionWithWrongHttpMethod
     *
     * @param   string  $httpMethod
     */
    public function testThatTraitsThrowsAnExceptionWithWrongHttpMethod(string $httpMethod)
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var CountTestClass|\PHPUnit_Framework_MockObject_MockObject $countTestClass */
        $countTestClass = $this->getMockForAbstractClass(CountTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/count', $httpMethod);

        $countTestClass->countMethod($request)->getContent();
    }

    public function testThatTraitCallsProcessCriteriaIfItExists()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var CountTestClass|\PHPUnit_Framework_MockObject_MockObject $countTestClass */
        $countTestClass = $this->getMockForAbstractClass(
            CountTestClass::class, [$resourceService, $restHelperResponse],
            '', true, true, true, ['processCriteria']
        );

        // Create request
        $request = Request::create('/count', 'GET');

        $countTestClass
            ->expects(static::once())
            ->method('processCriteria')
            ->withAnyParameters()
        ;

        $countTestClass->countMethod($request)->getContent();
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitsThrowsAnExceptionWithWrongHttpMethod(): array
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
}
