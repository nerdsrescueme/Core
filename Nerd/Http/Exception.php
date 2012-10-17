<?php

/**
 * The Http namespace aims to provide convenient and powerful tools for working
 * with data in an http context (both sending an recieving).
 *
 * @package    Nerd
 * @subpackage Http
 */
namespace Nerd\Http;

// Aliasing rules
use Nerd\Config;

/**
 * Exception class
 *
 * The Http Exception class is meant to provide a simplistic approach to sending
 * Http errors to the browser. Although you can easily accomplish the same thing
 * by utilizing Response directly, this class allows you to execute a an error
 * within an `throw Http\Exception()` statement.
 *
 * @package    Nerd
 * @subpackage Http
 */
class Exception extends \Exception
{
    /**
     * Create a new response rendered with the error code, and stops page
     * execution.
     *
     * ## Usage
     *
     *     throw new \Http\Exception(500);
     *
     * @param    integer          The HTTP error code, defaults to 404
     * @param    Response         The current response object, if one is not defined, one will be generated
     * @return void No value is returned
     */
    public function __construct($code = 404, Response $response = null)
    {
        if ($code < 400) {
            throw new \Exception('An invalid call to Nerd\\Http\\Exception was made. This exception is meant to handle errors for Http, and was called with a '.$code);
        }

        if ($response === null) {
            $response = new Response();
        }

        $response
            ->setStatus($code)
            ->setBody(call_user_func(Config::get('error.http_error_handler', null, false), $code))
            ->send();

        exit;
    }
}
