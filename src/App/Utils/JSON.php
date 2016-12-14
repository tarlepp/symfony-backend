<?php
declare(strict_types = 1);
/**
 * /src/App/Utils/JSON.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Utils;

/**
 * Class JSON
 *
 * @package App\Util
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JSON
{
    /**
     * Generic JSON encode method with error handling support.
     *
     * @see http://php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @throws  \LogicException
     *
     * @param   mixed   $input      The value being encoded. Can be any type except a resource.
     * @param   integer $options    Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS,
     *                              JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT,
     *                              JSON_PRESERVE_ZERO_FRACTION, JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR.
     *                              The behaviour of these constants is described on the JSON constants page.
     * @param   integer $depth      Set the maximum depth. Must be greater than zero.
     *
     * @return  string
     */
    public static function encode($input, int $options = 0, int $depth = 512): string
    {
        $output = json_encode($input, $options, $depth);

        self::handleError();

        return $output;
    }

    /**
     * Generic JSON decode method with error handling support.
     *
     * @see http://php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @throws  \LogicException
     *
     * @param   string  $json       The json string being decoded.
     * @param   boolean $assoc      When TRUE, returned objects will be converted into associative arrays.
     * @param   integer $depth      User specified recursion depth.
     * @param   integer $options    Bitmask of JSON decode options. Currently only JSON_BIGINT_AS_STRING is supported
     *                              (default is to cast large integers as floats)
     *
     * @return  \stdClass|array|mixed
     */
    public static function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $output = json_decode($json, $assoc, $depth, $options);

        self::handleError();

        return $output;
    }

    /**
     * Helper method to handle possible errors within json_encode and json_decode functions.
     *
     * @return  void
     */
    private static function handleError()
    {
        // Get last JSON error
        $error = json_last_error();

        // Oh noes, some error happened
        if ($error !== JSON_ERROR_NONE) {
            throw new \LogicException(self::getErrorMessage($error) . ' - ' . json_last_error_msg());
        }
    }

    /**
     * Helper method to convert JSON error constant to human-readable-format.
     *
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @param   integer $error
     *
     * @return  string
     */
    private static function getErrorMessage(int $error): string
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                $output = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_SYNTAX:
                $output = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $output = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $output = $error;
                break;
        }

        return $output;
    }
}
