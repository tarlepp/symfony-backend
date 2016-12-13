<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/DeleteTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Traits\Rest\Methods\Delete;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

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
}
