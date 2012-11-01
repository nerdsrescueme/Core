<?php

/**
* Session handler namespace
*
* @package Nerd
* @subpackage Session
*/
namespace Nerd\Session\Handler;

/**
 * Void session handler
 *
 * Sends empty data and only pretends to set real data to the session

 * @package Nerd
 * @subpackage Session
 */
class Void implements \SessionHandlerInterface
{
    /**
     * Open session
     *
     * @param    string          Path to session file
     * @param    string          Session id
     * @return   boolean
     */
    public function open($path, $id)
    {
        return true;
    }

    /**
     * Close session
     *
     * @return void
     */
    public function close()
    {
        // ...
    }

    /**
     * Read session data
     *
     * @param    string          Session id
     * @return   string
     */
    public function read($id)
    {
        return '';
    }

    /**
     * Write session data
     *
     * @param    string          Session id
     * @param    string          Serialized session data
     * @return   boolean
     */
    public function write($id, $data)
    {
        return true;
    }

    /**
     * Destroy session
     *
     * @param    string          Session id
     * @return   boolean
     */
    public function destroy($id)
    {
        return true;
    }

    /**
     * Session garbage collection
     *
     * @param    integer          Session lifetime
     * @return void
     */
    public function gc($life)
    {
        // ...
    }
}
