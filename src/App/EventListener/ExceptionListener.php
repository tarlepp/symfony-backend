<?php
declare(strict_types = 1);
/**
 * /src/App/EventListener/ExceptionListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Utils\JSON;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionListener
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $environment;

    /**
     * ExceptionListener constructor.
     *
     * @param   LoggerInterface $logger
     * @param   string          $environment
     */
    public function __construct(LoggerInterface $logger, string $environment)
    {
        $this->logger = $logger;
        $this->environment = $environment;
    }

    /**
     * Listener method for all exceptions.
     *
     * @throws  \InvalidArgumentException
     * @throws  \LogicException
     * @throws  \UnexpectedValueException
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
        } else if ($exception instanceof AuthenticationException) {
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->headers->set('Content-Type', 'application/json');

        // Set base of error message
        $error = [
            'message'   => $this->getExceptionMessage($exception),
            'code'      => $exception->getCode(),
            'status'    => $response->getStatusCode(),
        ];

        // Attach more info to error response in dev environment
        if ($this->environment === 'dev') {
            $error += [
                'debug' => [
                    'file'          => $exception->getFile(),
                    'line'          => $exception->getLine(),
                    'message'       => $exception->getMessage(),
                    'trace'         => $exception->getTrace(),
                    'traceString'   => $exception->getTraceAsString(),
                ]
            ];
        }

        $response->setContent(JSON::encode($error));

        // Send the modified response object to the event
        $event->setResponse($response);
    }

    /**
     * Helper method to convert exception message for user. This method is used in 'production' environment so, that
     * application won't reveal any sensitive error data to users.
     *
     * @todo    What else use cases this should handle?
     *
     * @param   \Exception  $exception
     *
     * @return  string
     */
    private function getExceptionMessage(\Exception $exception): string
    {
        if ($this->environment === 'dev') {
            return $exception->getMessage();
        }

        // Within AccessDeniedHttpException we need to hide actual real message from users
        if ($exception instanceof AccessDeniedHttpException) {
            $message = 'Access denied.';
        } elseif ($exception instanceof DBALException || $exception instanceof ORMException) { // Database errors
            $message = 'Database error.';
        } else {
            $message = $exception->getMessage();
        }

        return $message;
    }
}
