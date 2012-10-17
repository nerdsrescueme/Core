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

class Deferred
{
    public static function when(callable $func)
    {
        $args = func_get_args() and array_shift($args);
        $promise = call_user_func_array($func, $args);

        return new static($promise);
    }

    private $args = [];
    private $promise;

    private $done = [];
    private $fail = [];
    private $always = [];
    private $progress = [];

    public function __construct(Promise $promise = null)
    {
        $this->promise = $promise ?: new Promise();
    }

    public function then(callable $done, callable $fail = null, callable $always = null)
    {
        $this->done($done);

        $fail   and $this->fail($fail);
        $always and $this->always($always);

        // execute???
        return $this;
    }

    public function done(callable $callback)
    {
        // Execute immediately if already resolved.
        if ($this->isResolved()) {
            call_user_func_array($callback, $this->args);
        }

        $this->done[] = $callback;

        return $this;
    }

    public function fail(callable $callback)
    {
        // Execute immediately if already rejected.
        if ($this->isRejected()) {
            call_user_func_array($callback, $this->args);
        }

        $this->fail[] = $callback;

        return $this;
    }

    public function always(callable $callback)
    {
        // Execute immediately if not pending.
        if (!$this->isPending()) {
            call_user_func_array($callback, $this->args);
        }

        $this->always[] = $callback;

        return $this;
    }

    public function progress(callable $progress)
    {
        $this->progress[] = $progress;

        return $this;
    }

    public function promise()
    {
        return $this->promise;
    }

    public function isPending()
    {
        return $this->promise->state() === Promise::STATE_PENDING;
    }

    public function isRejected()
    {
        return $this->promise->state() === Promise::STATE_REJECTED;
    }

    public function isResolved()
    {
        return $this->promise->state() === Promise::STATE_RESOLVED;
    }

    public function notify()
    {
        $args = func_get_args();
        $this->_execute($this->progress, null, $args);
    }

    public function notifyWith(Promise $context)
    {
        $args = func_get_args() and array_shift($args);
        $this->_execute($this->progress, $context, $args);
    }

    public function reject()
    {
        $this->promise->reject();
        $this->args = func_get_args();
        $this->_execute($this->fail);
        $this->_execute($this->always);
    }

    public function rejectWith(Promise $context)
    {
        $context->reject();
        $this->args = func_get_args() and array_shift($this->args);
        $this->_execute($this->fail, $context);
        $this->_execute($this->always, $context);
    }

    public function resolve()
    {
        $this->promise->resolve();
        $this->args = func_get_args();
        $this->_execute($this->done);
        $this->_execute($this->always);
    }

    public function resolveWith(Promise $context)
    {
        $context->resolve();
        $this->args = func_get_args() and array_shift($this->args);
        $this->_execute($this->done, $context);
        $this->_execute($this->always, $context);
    }

    public function state()
    {
        return $this->promise->state;
    }

    private function _execute(array &$on, Promise $context = null, array $args = null)
    {
        if ($context === null) {
            $context = $this->promise;
        }

        if ($args === null) {
            $args = $this->args;
        }

        $args[] = $context;

        foreach ($on as $func) {
            call_user_func_array($func, $args);
        }
    }
}
