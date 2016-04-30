<?php
/**
 * /src/App/EventListener/ExceptionListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

// Symfony components
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Class ExceptionListener
 *
 * @category    Listener
 * @package     App\EventListener
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionListener
{
    /**
     * @var \AppKernel
     */
    protected $kernel;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * ExceptionListener constructor.
     *
     * @param   KernelInterface         $kernel
     * @param   DebugLoggerInterface    $logger
     */
    public function __construct(KernelInterface $kernel, DebugLoggerInterface $logger)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    /**
     * Listener method for all exceptions.
     *
     * @param   GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Get exception from current event
        $exception = $event->getException();

        // Log exception
        $this->logger->error((string)$exception);

        // Create new response
        $response = new Response();

        // HttpExceptionInterface is a special type of exception that holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->headers->set('Content-Type', 'application/json');

        // Set base of error message
        $error = [
            'message'   => $exception->getMessage(),
            'code'      => $exception->getCode(),
            'status'    => $response->getStatusCode(),
        ];

        // Attach more info to error response in dev environment
        if ($this->kernel->getEnvironment() === 'dev') {
            $error += [
                'debug' => [
                    'file'          => $exception->getFile(),
                    'line'          => $exception->getLine(),
                    'trace'         => $exception->getTrace(),
                    'traceString'   => $exception->getTraceAsString(),
                ]
            ];
        }

        $response->setContent(json_encode($error));

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
