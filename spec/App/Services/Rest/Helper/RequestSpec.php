<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/Rest/Helper/RequestSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services\Rest\Helper;

use App\Services\Rest\Helper\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class RequestSpec
 * @package spec\App\Services\Rest\Helper
 */
class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_throw_exception_when_calling_getCriteria_with_not_json_content(
        HttpRequest $request
    ) {
        $request->get('where', Argument::any())->shouldBeCalled()->willReturn('not valid JSON');

        $this->shouldThrow(HttpException::class)->during('getCriteria', [$request]);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_empty_array_when_calling_getCriteria(
        HttpRequest $request
    ) {
        $request->get('where', Argument::any())->shouldBeCalled()->willReturn('{}');

        self::getCriteria($request)->shouldReturn([]);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_expected_array_when_calling_getCriteria(
        HttpRequest $request
    ) {
        $request->get('where', Argument::any())->shouldBeCalled()->willReturn('{"foo": "foo1", "bar": "bar1"}');

        self::getCriteria($request)->shouldHaveKey('foo');
        self::getCriteria($request)->shouldHaveKey('bar');
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_null_when_calling_getOrderBy_without_order_parameter(
        HttpRequest $request
    ) {
        self::getOrderBy($request)->shouldReturn(null);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_array_when_calling_getOrderBy_with_order_parameter(
        HttpRequest $request
    ) {
        $request->get('order', Argument::any())->shouldBeCalled()->willReturn('-foobar');

        self::getOrderBy($request)->shouldReturn(['foobar' => 'DESC']);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_null_when_calling_getLimit_without_limit_parameter(
        HttpRequest $request
    ) {
        self::getLimit($request)->shouldReturn(null);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|HttpRequest $request
     */
    function it_should_return_positive_value_when_calling_getLimit_with_limit_parameter(
        HttpRequest $request
    ) {
        $request->get('limit', Argument::any())->shouldBeCalled()->willReturn(-10);

        self::getLimit($request)->shouldReturn(10);
    }
}
