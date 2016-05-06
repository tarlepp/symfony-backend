<?php
/**
 * /src/App/Util/JSON.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Util;

/**
 * Class JSON
 *
 * @category    Utils
 * @package     App\Util
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
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
    public static function encode($input, $options = 0, $depth = 512)
    {
        // Decode given JSON string to object
        $output = json_encode($input, $options, $depth);

        // Get last JSON error
        $error = json_last_error();

        // Oh noes, some error happened
        if ($error !== JSON_ERROR_NONE) {
            self::throwException($error);
        }

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
    public static function decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        // Decode given JSON string to object
        $output = json_decode($json, $assoc, $depth, $options);

        // Get last JSON error
        $error = json_last_error();

        // Oh noes, some error happened
        if ($error !== JSON_ERROR_NONE) {
            self::throwException($error);
        }

        return $output;
    }

    /**
     * Generic method to throw an exception about encode and decode methods.
     *
     * @param   integer $error
     *
     * @return  void
     */
    private static function throwException($error)
    {
        throw new \LogicException(self::getErrorMessage($error) . ' - ' . json_last_error_msg());
    }

    /**
     * Helper method to convert JSON error constant to
     *
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @param   integer $error
     *
     * @return  string
     */
    private static function getErrorMessage($error)
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                $output = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $output = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $output = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $output = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $output = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            case JSON_ERROR_RECURSION:
                $output = 'One or more recursive references in the value to be encoded';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $output = 'One or more NAN or INF values in the value to be encoded';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $output = 'A value of a type that cannot be encoded was given';
                break;
            default:
                $output = 'Unknown error';
                break;
        }

        return $output;
    }
}
