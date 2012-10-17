<?php

/**
* Session handler namespace
*
* @package Nerd
* @subpackage Session
*/
namespace Nerd\Session\Handler;

// Aliasing rules
use \Nerd\Config;

/**
 * Session database handler
 *
 * Uses the default Nerd database connection to manage sessions in the database.

 * @package Nerd
 * @subpackage Session
 */
class MySQL implements \SessionHandlerInterface
{
    // Traits
    use \Nerd\Design\Connectable;

    public $created_at;

    /**
     * Open session
     *
     * @param    string          Path to session file
     * @param    string          Session id
     * @return boolean
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
        static::$connection = null;
    }

    /**
     * Read session data
     *
     * @param    string          Session id
     * @return string
     */
    public function read($id)
    {
        $table = Config::get('session.mysql.table', 'nerd_sessions');
        $query = static::$connection->prepare("SELECT * FROM $table WHERE id = ? LIMIT 1");

        if ($query->execute(array($id)) and $session = $query->fetchObject()) {
            $this->created_at = $session->created_at;

            return $session->data;
        }

        return '';
    }

    /**
     * Write session data
     *
     * @param    string          Session id
     * @param    string          Serialized session data
     * @return boolean
     */
    public function write($id, $data)
    {
        $table = Config::get('session.mysql.table', 'nerd_sessions');
        $sql   = $this->created_at === null
               ? "INSERT INTO $table (id, data, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)"
               : "UPDATE $table SET id = ?, data = ?, updated_at = CURRENT_TIMESTAMP WHERE id = '$id'";
        $query = static::$connection->prepare($sql);

        return (boolean) $query->execute([$id, $data]);
    }

    /**
     * Destroy session
     *
     * @param    string          Session id
     * @return boolean
     */
    public function destroy($id)
    {
        $table = Config::get('session.mysql.table', 'nerd_sessions');
        $query = static::$connection->prepare("DELETE FROM $table WHERE id = ? LIMIT 1");

        return (boolean) $query->execute(array($id));
    }

    /**
     * Session garbage collection
     *
     * @param    integer          Session lifetime
     * @return void
     */
    public function gc($life)
    {
        $table = Config::get('session.mysql.table', 'nerd_sessions');
        static::$connection->query("DELETE FROM $table WHERE updated_at < DATE_SUB(NOW(), INTERVAL $life SECOND)");
    }
}
