<?php
declare(strict_types = 1);
/**
 * /spec/App/Services/ResponseLoggerSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Services;

use App\Services\Rest\RequestLog as RequestLogService;
use App\Services\Interfaces\ResponseLogger as ResponseLoggerInterface;
use App\Services\ResponseLogger;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

/**
 * Class ResponseLoggerSpec
 *
 * @package spec\App\Services
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ResponseLoggerSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|LoggerInterface   $logger
     * @param   \PhpSpec\Wrapper\Collaborator|RequestLogService $service
     */
    function let(
        LoggerInterface $logger,
        RequestLogService $service
    ) {
        $this->beConstructedWith($logger, $service, 'spec');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseLogger::class);
        $this->shouldImplement(ResponseLoggerInterface::class);
    }
}
