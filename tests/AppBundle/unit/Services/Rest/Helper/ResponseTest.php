<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/unit/Services/Rest/Helper/ResponseTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\unit\Services\Rest\Helper;

use App\Services\Rest\Helper\Response;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResponseTest
 *
 * @package AppBundle\unit\Services\Rest\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseTest extends KernelTestCase
{
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
}
