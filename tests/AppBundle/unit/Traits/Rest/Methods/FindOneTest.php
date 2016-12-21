<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/Traits/Rest/Methods/FindOneTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\Traits\Rest\Methods;

use App\Entity\Interfaces\EntityInterface;
use App\Traits\Rest\Methods\FindOne;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\unit\Traits\Rest\Methods\FindOne as FindOneTestClass;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__FILE__) . '/FindOne.php';

/**
 * Class FindOneTest
 *
 * @package AppBundle\unit\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class FindOneTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\FindOne trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $uuid = Uuid::uuid4()->toString();
        $mock = $this->getMockForTrait(FindOne::class);
        $request = Request::create('/' . $uuid, 'GET');

        $mock->findOneMethod($request, $uuid);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var FindOneTestClass|\PHPUnit_Framework_MockObject_MockObject $findOneTestClass */
        $findOneTestClass = $this->getMockForAbstractClass(
            FindOneTestClass::class,
            [$resourceService, $restHelperResponse]
        );

        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $entityInterface = $this->createMock(EntityInterface::class);

        $uuid = Uuid::uuid4()->toString();

        $request
            ->expects(static::once())
            ->method('getMethod')
            ->willReturn('GET');

        $resourceService
            ->expects(static::once())
            ->method('findOne')
            ->with($uuid, true)
            ->willReturn($entityInterface);

        $restHelperResponse
            ->expects(static::once())
            ->method('createResponse')
            ->withAnyParameters()
            ->willReturn($response);

        $findOneTestClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $findOneTestClass
            ->expects(static::once())
            ->method('getResponseService')
            ->willReturn($restHelperResponse);

        $findOneTestClass->findOneMethod($request, $uuid);
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

        /** @var FindOneTestClass|\PHPUnit_Framework_MockObject_MockObject $findOneTestClass */
        $findOneTestClass = $this->getMockForAbstractClass(
            FindOneTestClass::class,
            [$resourceService, $restHelperResponse]
        );

        // Create request and response
        $request = Request::create('/', $httpMethod);

        $findOneTestClass->findOneMethod($request, Uuid::uuid4()->toString());
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitThrowsAnExceptionWithWrongHttpMethod(): array
    {
        return [
            ['HEAD'],
            ['DELETE'],
            ['PUT'],
            ['POST'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];
    }
}
