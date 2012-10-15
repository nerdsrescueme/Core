<?php

/**
 * Nerd Database Namespace
 *
 * @package    Nerd
 * @subpackage DB
 */
namespace Nerd\DB;

// Aliasing rules

/**
 * Exception class
 *
 * @package    Nerd
 * @subpackage DB
 */
class Exception extends \PDOException
{
    /**
     * Explain
     *
     * ## Usage
     *
     *     throw new \DB\Exception('A database error has occured');
     *
     * @param    integer          The error message
     * @param    Response         The current response object, if one is not defined, one will be generated
     * @return void No value is returned
     */
    public function __construct($message = null, $code = null)
    {
        if ($message === null) {
            $message = 'An unknown error has occurred.';
        }

        $params = func_get_args();
        $code   = 'DB-0';

        if ($params[0] instanceof \PDOException) {
            $e = $params[0];

            if (strstr($e->getMessage(), 'SQLSTATE[')) {
                preg_match('/SQLSTATE\[(\w+)\]\: (\[\w+\])?\s?(.*)/', $e->getMessage(), $matches);
                $code = ($matches[1] == 'HT000' ? $matches[2] : $matches[1]);
                $message = $matches[3];
            }
        }

        $this->code = $code;
        $this->message = $message;
    }
}
