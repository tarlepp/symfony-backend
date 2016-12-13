<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/UpdateTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Traits\Rest\Methods\Update;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

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
}
