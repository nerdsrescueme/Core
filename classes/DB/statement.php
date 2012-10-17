<?php

/**
 * Nerd Database Namespace
 *
 * @package    Nerd
 * @subpackage DB
 */
namespace Nerd\DB;

/**
 * Database Statement Class
 *
 * It is very important that you understand the PHP PDO classes in order to
 * understand how the Nerd Database layer operates. Essentially, the Nerd DB
 * layer is an extension of the PDO classes.
 *
 * @package    Nerd
 * @subpackage DB
 */
class Statement extends \PDOStatement
{
    /**
     * Database connection instance
     *
     * @var    object
     */
    public $dbh;

    /**
     * Class constructor
     *
     * @param     \Nerd\DB     Database connection instance
     * @return void
     */
    protected function __construct(\Nerd\DB $connection)
    {
        $this->dbh = $connection;
    }
}
