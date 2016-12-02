<?php
declare(strict_types = 1);
/**
 * /spec/App/Controller/DefaultControllerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Controller;

use App\Controller\DefaultController;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerSpec
 *
 * @mixin DefaultController
 *
 * @package spec\App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultController::class);
    }

    function it_should_response_to_index_action()
    {
        /** @var ObjectBehavior $response */
        $response = $this->indexAction();

        $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
    }

    function it_should_response_http_status_code_200_to_index_action()
    {
        $response = $this->indexAction();

        $response->getStatusCode()->shouldReturn(200);
    }

    function it_should_response_empty_body_to_index_action()
    {
        $response = $this->indexAction();

        $response->getContent()->shouldEqual('');
    }
}
