<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Abstract Http class
 *
 * The Http clas provides functionality and information that can be implemented
 * into any class meant to provide more specific http functionality.
 *
 * @package    Nerd
 * @subpackage Core
 */
abstract class HTTP
{
    /**
     * HTTP status codes
     *
     * The following is a list of HyperText Transfer Protocol (HTTP) response
     * status codes. This includes codes for IETF internet standards as well as
     * unstandardised RFCs, other specifications and some additional commonly
     * used codes.
     *
     * @var    array
     */
    public static $statuses = [
        // 1xx Information
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // WebDAV (RFC 2518)
        103 => 'Checkpoint',
        122 => 'Request-URI Too Long',
        // 2xx Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // WebDAV (RFC 4918)
        208 => 'Already Reported', // WebDAV (RFC 5842)
        226 => 'IM Used', // (RFC 3229)
        // 3xx Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        206 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Resume Incomplete',
        // 4xx Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // WebDAV (RFC 2324)
        422 => 'Unprocessable Entity', // WebDAV (RFC 4918)
        423 => 'Locked', // WebDAV (RFC 4918)
        424 => 'Failed Dependency', // WebDAV (RFC 4918)
        425 => 'Unordered Collection', // WebDAV (RFC 3648)
        426 => 'Upgrade Required', // (RFC 2817)
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'No Response',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        499 => 'Client Closed Request',
        // 5xx Server Error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        406 => 'Variant Also Negotiates', // (RFC 2295)
        507 => 'Insufficient Storage', // WebDAV (RFC 4918)
        508 => 'Loop Detected', // WebDAV (RFC 5842)
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended', // (RFC 2774)
        511 => 'Network Authentication Required',
        598 => 'Network Read Timeout Error', // Informal Convention
        599 => 'Network connect Timeout Error', // Informal Convention
    ];
}
