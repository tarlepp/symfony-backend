<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/UpdateTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Entity\Interfaces\EntityInterface;
use App\Traits\Rest\Methods\Update;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use AppBundle\Traits\Rest\Methods\Update as UpdateTestClass;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__FILE__) . '/Update.php';

/**
 * Class UpdateTest
 *
 * @package AppBundle\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UpdateTest extends KernelTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You cannot use App\Traits\Rest\Methods\Update trait within class that does not implement App\Controller\Interfaces\RestController interface.
     */
    public function testThatTraitThrowsAnException()
    {
        $uuid = Uuid::uuid4()->toString();
        $mock = $this->getMockForTrait(Update::class);
        $request = Request::create('/' . $uuid, 'PUT');

        $mock->updateMethod($request, $uuid);
    }

    public function testThatTraitCallsServiceMethods()
    {
        $resourceService = $this->createMock(ResourceServiceInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $entityInterface = $this->createMock(EntityInterface::class);

        /** @var UpdateTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(UpdateTestClass::class, [$resourceService, $restHelperResponse]);

        $uuid = Uuid::uuid4()->toString();

        $request
            ->expects(static::once())
            ->method('getContent')
            ->willReturn('{"foo":"bar"}');

        $request
            ->expects(static::once())
            ->method('getMethod')
            ->willReturn('PUT');

        $resourceService
            ->expects(static::once())
            ->method('update')
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

        $testClass->updateMethod($request, $uuid)->getContent();
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

        /** @var UpdateTestClass|\PHPUnit_Framework_MockObject_MockObject $testClass */
        $testClass = $this->getMockForAbstractClass(UpdateTestClass::class, [$resourceService, $restHelperResponse]);

        // Create request and response
        $request = Request::create('/', $httpMethod);

        $uuid = Uuid::uuid4()->toString();

        $testClass->updateMethod($request, $uuid)->getContent();
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTraitThrowsAnExceptionWithWrongHttpMethod(): array
    {
        return [
            ['HEAD'],
            ['GET'],
            ['POST'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];
    }
}
