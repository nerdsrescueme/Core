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
 * Collection Class
 *
 * The collection class provides extentions to Nerd's basic enumerable class. These
 * methods allow more rich searching and a more simple approach to adding data to the
 * enumerable array.
 *
 * @package Nerd
 * @subpackage Core
 */
class Collection extends \Nerd\Design\Enumerable
{
    /**
     * Add an item to the enumerable array
     *
     * @param     mixed     Item to add to the collection
     * @param     boolean   Prepend the item to the collection
     * @return void
     */
    public function add($item, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->enumerable, $item);
        } else {
            $this->enumerable []= $item;
        }

        return $this;
    }

    /**
     * Get values from the enumerable array with keys between $start
     * and $end
     *
     * @param     integer     Starting key
     * @param     integer     Ending key
     * @param     boolean     Keep the current key indexes
     * @return array Segment of enumerable array
     */
    public function between($start, $end, $preserve_keys = true)
    {
        $result = [];

        foreach ($this as $key => $value) {
            if ($key >= $start and $key <= $end) {
                if ($preserve_keys) {
                    $result[$key] = $value;
                    continue;
                }

                $result []= $value;
            }
        }

        return $result;
    }

    /**
     * Alias for Collection::add($item, true)
     *
     * @see \Nerd\Design\Collection::add()
     */
    public function prepend($items)
    {
        $this->add($items, true);
    }

    /**
     * Remove an item from the collection array
     *
     * @param     mixed     Item(s) to remove from the collection
     * @return void
     */
    public function remove($offsets = null)
    {
        if ($offsets === null) {
            return $this->enumerable = [];
        }

        $offsets = (array) $offsets;

        foreach ($offsets as $offset) {
            $this->offsetUnset($offset);
        }
    }

    /**
     * Replace an item in the collection array
     *
     * @param    mixed          Item to search for
     * @param    mixed          Replacement value
     * @return boolean Was the replacement successful?
     */
    public function replace($current, $replacement)
    {
        $lamda = function($value) use (&$current) {
            return $current === $value;
        };

        $index = $this->index($lamda);

        if (!$index or !isset($this->enumerable[$index])) {
            return false;
        }

        $this->enumerable[$index] = $replacement;

        return true;
    }

    /**
     * Return the first entry in the collection array, alternatively returning
     * the first entry to match $lambda if it is supplied.
     *
     * @param     \callable     Used to evaluate enum values, returns boolean
     * @return mixed The first value to find or match
     */
    public function first(callable $lambda = null)
    {
        $lambda === null and $lambda = function() { return true; };

        $this->rewind();

        return $this->find($lambda);
    }

    /**
     * Get an array of this collections array keys
     *
     * @return array Array keys for this collection
     */
    public function keys()
    {
        return array_keys($this->enumerable);
    }

    /**
     * Return the last item in the collection array, alternatively returning
     * the last item to match $lambda if it is supplied.
     *
     * @param     callable     Used to evaluate enum values, returns boolean
     * @return mixed The last value to find or match
     */
    public function last(callable $lambda = null)
    {
        $lambda === null and $lambda = function() { return true; };

        $result = $this->findAll($lambda);

        return end($result);
    }

    /**
     * Get an array of this collections array values
     *
     * @return array Array values for this collection
     */
    public function values()
    {
        return array_values($this->enumerable);
    }

    /**
     * Convert the collection array to a different format using Nerd's
     * format classes.
     *
     * @param     string     Formatter to use
     * @param     mixed      Flags for the selected formatter
     * @return mixed Formatter version of the collection array
     */
    public function to($format, $flags = null)
    {
        return \Nerd\Format::instance($format)->to($this->enumerable);
    }
}
