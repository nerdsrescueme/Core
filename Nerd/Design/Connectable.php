<?php

/**
 * Design namespace. This namespace is meant to provide abstract concepts and in
 * most cases, simply interfaces that in someway structures the general design
 * used in core components. Additionally, the Design namespace provides sub
 * namespaces that relate specifically to common design patterns that can be
 * attached to classes without duplication of functionality.
 *
 * @package Nerd
 * @subpackage Design
 */
namespace Nerd\Design;

/**
 * Connectable trait
 *
 * The trait allows a class to connect to a given Nerd Database instance
 *
 * @package Nerd
 * @subpackage Core
 */
trait Connectable
{
    /**
     * Nerd DB connection instance
     *
     * @var Nerd\DB
     */
    protected static $connection;

    /**
     * Inheritable class constructor
     *
     * If no connection has been set on the static class, then set one using the
     * default DB connection by default.
     *
     * @param    string          DB connection identifier
     */
    public function __construct($connection = null)
    {
        if (static::$connection === null) {
            static::$connection = \Nerd\DB::connection($connection);
        }
    }
}
