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

trait Eventable
{
    public function bindEvent($event, callable $func)
    {
        return \Nerd\Event::instance()->bind($event, $func);
    }

    public function unbindEvent($event)
    {
        return \Nerd\Event::instance()->unbind($event);
    }

    public function triggerEvent($event, array $args = [])
    {
        return \Nerd\Event::instance()->trigger($event, $args);
    }
}
