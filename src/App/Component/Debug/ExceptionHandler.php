<?php
/**
 * /src/App/Component/Debug/ExceptionHandler.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Component\Debug;

use Symfony\Component\Debug\ExceptionHandler as Base;
use Symfony\Component\Debug\Exception\FlattenException;

/**
 * Class ExceptionHandler
 *
 * @category    Core
 * @package     App\Core
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ExceptionHandler extends Base
{
    /**
     * Is application run in debug mode or not.
     *
     * @var bool
     */
    private $debug;

    /**
     * ExceptionHandler constructor. This is needed to get 'debug' information to this class context.
     *
     * @param   bool        $debug
     * @param   null|string $charset
     * @param   null|string $fileLinkFormat
     */
    public function __construct($debug = true, $charset = null, $fileLinkFormat = null)
    {
        $this->debug = $debug;

        parent::__construct($debug, $charset, $fileLinkFormat);
    }

    /**
     * Sends the error associated with the given Exception as a plain PHP response. This method uses plain PHP
     * functions like header() and echo to output the response.
     *
     * Note that this will just generate pure JSON response of an error.
     *
     * @param   \Exception|FlattenException $exception  An \Exception or FlattenException instance
     *
     * @return  void
     */
    public function sendPhpResponse($exception)
    {
        // Store trace string
        $traceString = $exception->getTraceAsString();

        // Flatten current Exception
        if (!$exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        // Set headers, if those aren't yet set
        if (!headers_sent()) {
            header(sprintf('HTTP/1.0 %s', $exception->getStatusCode()));

            foreach ($exception->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }

            header('Content-Type: application/json; charset=UTF-8');
        }

        // Basic error data
        $error = [
            'message'   => $exception->getMessage(),
            'status'    => $exception->getStatusCode(),
            'code'      => $exception->getCode(),
        ];

        // If we're running application in debug mode, attach some extra information about actual error
        if ($this->debug) {
            $error += [
                'debug' => [
                    'file'          => $exception->getFile(),
                    'line'          => $exception->getLine(),
                    'trace'         => $exception->getTrace(),
                    'traceString'   => $traceString,
                ]
            ];
        }

        echo json_encode($error);
    }
}
