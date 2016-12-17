<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/DeleteTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Entity\Interfaces\EntityInterface;
use App\Traits\Rest\Methods\Delete;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\Traits\Rest\Methods\Delete as DeleteTestClass;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__FILE__) . '/Delete.php';

/**
 * Class DeleteTest
 *
 * @package AppBundle\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DeleteTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\Delete trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $uuid = Uuid::uuid4()->toString();
        $mock = $this->getMockForTrait(Delete::class);
        $request = Request::create('/' . $uuid, 'DELETE');

        $mock->deleteMethod($request, $uuid);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $entityInterface = $this->createMock(EntityInterface::class);

        /** @var DeleteTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(DeleteTestClass::class, [$resourceService, $restHelperResponse]);

        $resourceService
            ->expects(static::once())
            ->method('delete')
            ->withAnyParameters()
            ->willReturn($entityInterface);

        $restHelperResponse
            ->expects(static::once())
            ->method('createResponse')
            ->withAnyParameters()
            ->willReturn($response);

        $request
            ->expects(static::once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $testClass
            ->expects(static::once())
            ->method('getResourceService')
            ->willReturn($resourceService);

        $testClass
            ->expects(static::once())
            ->method('getResponseService')
            ->willReturn($restHelperResponse);

        $testClass->deleteMethod($request, Uuid::uuid4()->toString());
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

        /** @var DeleteTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(DeleteTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/', $httpMethod);

        $testClass->deleteMethod($request, Uuid::uuid4()->toString());
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
            ['POST'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];
    }
}
