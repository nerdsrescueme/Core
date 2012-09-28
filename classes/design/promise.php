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

class Promise
{
	const STATE_PENDING  = 'pending';
	const STATE_RESOLVED = 'resolved';
	const STATE_REJECTED = 'rejected';

	private $state = 'pending';

	public function reject()
	{
		$this->setState(self::STATE_REJECTED);
		return $this;
	}

	public function resolve()
	{
		$this->setState(self::STATE_RESOLVED);
		return $this;
	}

	public function state()
	{
		return $this->state;
	}

	private function setState($state)
	{
		if ($this->state() !== self::STATE_PENDING)
		{
			throw new Exception('A Promise object may not reset its state.');
		}

		switch($state)
		{
			case self::STATE_RESOLVED :
				$this->state = self::STATE_RESOLVED;
				break;
			case self::STATE_REJECTED :
				$this->state = self::STATE_REJECTED;
				break;
			default :
				throw new Exception('The only settable promise states are "resolved" and "rejected".');
		}
	}
}