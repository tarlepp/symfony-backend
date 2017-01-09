<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Services/Rest/Helper/ResponseTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Services\Rest\Helper;

use App\Services\Rest\Helper\Response;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use App\Tests\ContainerTestCase;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResponseTest
 *
 * @package AppBundle\integration\Services\Rest\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseTest extends ContainerTestCase
{
    /**
     * @dataProvider dataProviderTestThatCreateResponseReturnsExpected
     *
     * @param   Request $request
     * @param   mixed   $data
     * @param   string  $expectedContent
     */
    public function testThatCreateResponseReturnsExpected(
        Request $request,
        $data,
        string $expectedContent
    ) {
        $serializer = $this->getContainer()->get('serializer');

        /** @var ResourceServiceInterface|\PHPUnit_Framework_MockObject_MockObject $stubResourceService */
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $responseClass = new Response($serializer);
        $responseClass->setResourceService($stubResourceService);

        $httpResponse = $responseClass->createResponse($request, $data, 200, null, null);

        static::assertSame($expectedContent, $httpResponse->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatNonSupportedSerializerFormatThrowsHttpException
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessageRegExp /The format ".*" is not supported for serialization\./
     *
     * @param string $format
     */
    public function testThatNonSupportedSerializerFormatThrowsHttpException(string $format)
    {
        $request = Request::create('');
        $serializer = $this->getContainer()->get('serializer');

        /** @var ResourceServiceInterface|\PHPUnit_Framework_MockObject_MockObject $stubResourceService */
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $responseClass = new Response($serializer);
        $responseClass->setResourceService($stubResourceService);

        $httpResponse = $responseClass->createResponse($request, ['foo' => 'bar'], 200, $format, null);

        $httpResponse->getContent();
    }

    public function testThatGetSerializeContextMethodCallsExpectedServiceMethods()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Serializer                 $stubSerializer
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                    $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|ParameterBag               $stubParameterBag
         * @var \PHPUnit_Framework_MockObject_MockObject|ResourceServiceInterface   $stubResourceService
         */
        $stubSerializer = $this->createMock(Serializer::class);
        $stubRequest = $this->createMock(Request::class);
        $stubParameterBag = $this->createMock(ParameterBag::class);
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $stubParameterBag
            ->expects(static::exactly(2))
            ->method('all')
            ->willReturn([]);

        $stubResourceService
            ->expects(static::once())
            ->method('getEntityName')
            ->willReturn('FakeEntity');

        $stubRequest->query = $stubParameterBag;

        $testClass = new Response($stubSerializer);
        $testClass->setResourceService($stubResourceService);
        $context = $testClass->getSerializeContext($stubRequest);

        static::assertEquals(['FakeEntity'], $context->attributes->get('groups')->get());
    }

    public function testThatGetSerializeContextSetExpectedGroupsWithPopulateAllParameterWhenEntityDoesNotHaveAnyAssociations()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Serializer                 $stubSerializer
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                    $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|ParameterBag               $stubParameterBag
         * @var \PHPUnit_Framework_MockObject_MockObject|ResourceServiceInterface   $stubResourceService
         */
        $stubSerializer = $this->createMock(Serializer::class);
        $stubRequest = $this->createMock(Request::class);
        $stubParameterBag = $this->createMock(ParameterBag::class);
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $stubParameterBag
            ->expects(static::exactly(2))
            ->method('all')
            ->willReturn(['populateAll' => '']);

        $stubResourceService
            ->expects(static::once())
            ->method('getEntityName')
            ->willReturn('FakeEntity');

        $stubRequest->query = $stubParameterBag;

        $testClass = new Response($stubSerializer);
        $testClass->setResourceService($stubResourceService);
        $context = $testClass->getSerializeContext($stubRequest);

        static::assertEquals(['Default'], $context->attributes->get('groups')->get());
    }

    public function testThatGetSerializeContextSetExpectedGroupsWithPopulateAllParameterWhenEntityDoesHaveAssociations()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Serializer                 $stubSerializer
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                    $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|ParameterBag               $stubParameterBag
         * @var \PHPUnit_Framework_MockObject_MockObject|ResourceServiceInterface   $stubResourceService
         */
        $stubSerializer = $this->createMock(Serializer::class);
        $stubRequest = $this->createMock(Request::class);
        $stubParameterBag = $this->createMock(ParameterBag::class);
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $stubParameterBag
            ->expects(static::exactly(2))
            ->method('all')
            ->willReturn(['populateAll' => '']);

        $stubResourceService
            ->expects(static::once())
            ->method('getEntityName')
            ->willReturn('FakeEntity');

        $stubResourceService
            ->expects(static::once())
            ->method('getAssociations')
            ->willReturn(['AnotherFakeEntity']);

        $stubRequest->query = $stubParameterBag;

        $testClass = new Response($stubSerializer);
        $testClass->setResourceService($stubResourceService);
        $context = $testClass->getSerializeContext($stubRequest);

        static::assertEquals(['Default', 'FakeEntity.AnotherFakeEntity'], $context->attributes->get('groups')->get());
    }

    public function testThatGetSerializeContextSetExpectedGroupsWithPopulateOnlyParameterWhenEntityDoesNotHaveAnyAssociations()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Serializer                 $stubSerializer
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                    $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|ParameterBag               $stubParameterBag
         * @var \PHPUnit_Framework_MockObject_MockObject|ResourceServiceInterface   $stubResourceService
         */
        $stubSerializer = $this->createMock(Serializer::class);
        $stubRequest = $this->createMock(Request::class);
        $stubParameterBag = $this->createMock(ParameterBag::class);
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $stubParameterBag
            ->expects(static::exactly(2))
            ->method('all')
            ->willReturn(['populateOnly' => '']);

        $stubResourceService
            ->expects(static::once())
            ->method('getEntityName')
            ->willReturn('FakeEntity');

        $stubRequest->query = $stubParameterBag;

        $testClass = new Response($stubSerializer);
        $testClass->setResourceService($stubResourceService);
        $context = $testClass->getSerializeContext($stubRequest);

        static::assertEquals(['FakeEntity'], $context->attributes->get('groups')->get());
    }

    public function testThatGetSerializeContextSetExpectedGroupsWithPopulateOnlyParameterWhenEntityDoesHaveAssociations()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Serializer                 $stubSerializer
         * @var \PHPUnit_Framework_MockObject_MockObject|Request                    $stubRequest
         * @var \PHPUnit_Framework_MockObject_MockObject|ParameterBag               $stubParameterBag
         * @var \PHPUnit_Framework_MockObject_MockObject|ResourceServiceInterface   $stubResourceService
         */
        $stubSerializer = $this->createMock(Serializer::class);
        $stubRequest = $this->createMock(Request::class);
        $stubParameterBag = $this->createMock(ParameterBag::class);
        $stubResourceService = $this->createMock(ResourceServiceInterface::class);

        $stubParameterBag
            ->expects(static::exactly(2))
            ->method('all')
            ->willReturn(['populateOnly' => '']);

        $stubResourceService
            ->expects(static::once())
            ->method('getEntityName')
            ->willReturn('FakeEntity');

        $stubRequest
            ->expects(static::once())
            ->method('get')
            ->with('populate')
            ->willReturn(['AnotherFakeEntity']);

        $stubRequest->query = $stubParameterBag;

        $testClass = new Response($stubSerializer);
        $testClass->setResourceService($stubResourceService);
        $context = $testClass->getSerializeContext($stubRequest);

        static::assertEquals(['AnotherFakeEntity'], $context->attributes->get('groups')->get());
    }

    /**
     * @return array
     */
    public function dataProviderTestThatCreateResponseReturnsExpected(): array
    {
        return [
            [
                Request::create(''),
                ['foo' => 'bar'],
                '{"foo":"bar"}'
            ],
            [
                Request::create('', 'GET', [], [], [], ['CONTENT_TYPE' => 'Some weird content type']),
                ['foo' => 'bar'],
                '{"foo":"bar"}'
            ],
            [
                Request::create('', 'GET', [], [], [], ['CONTENT_TYPE' => 'application/xml']),
                ['foo' => 'bar'],
                <<<DATA
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <entry><![CDATA[bar]]></entry>
</result>

DATA
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatNonSupportedSerializerFormatThrowsHttpException(): array
    {
        return [
            ['not supported format'],
            ['sjon'],
            ['lmx'],
        ];
    }
}
