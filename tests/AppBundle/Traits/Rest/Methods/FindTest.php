<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/FindTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Traits\Rest\Methods\Find;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FindTest
 *
 * @package AppBundle\Traits\Rest\Methods
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
}
