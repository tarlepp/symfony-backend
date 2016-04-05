<?php
/**
 * /src/App/EventListener/ExceptionListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

// Symfony components
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
     * ExceptionListener constructor.
     *
     * @param   \AppKernel $kernel
     */
    public function __construct(\AppKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Listener method for all exceptions.
     *
     * @todo Add logger OR should that be in app specified ExceptionHandler class?
     *
     * @param   GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Get exception from current event
        $exception = $event->getException();

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
