<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Controller/AuthControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Controller;

use App\Controller\AuthController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Tests\KernelTestCase;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthControllerTest
 *
 * @package AppBundle\integration\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthControllerTest extends KernelTestCase
{
    public function testThatGetTokenReturnsExpected()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|TokenStorageInterface          $tokenStorage
         * @var \PHPUnit_Framework_MockObject_MockObject|SerializerInterface            $serializer
         * @var \PHPUnit_Framework_MockObject_MockObject|RestHelperResponseInterface    $restHelperResponse
         */
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $serializer = $this->createMock(SerializerInterface::class);
        $restHelperResponse = $this->createMock(RestHelperResponseInterface::class);

        $controller = new AuthController($tokenStorage, $serializer, $restHelperResponse);

        static::assertNull($controller->getTokenAction());
    }
}
