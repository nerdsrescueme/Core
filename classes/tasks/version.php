<?php

namespace Nerd\Tasks;

use Nerd\Version as V;

class Version extends \Geek\Design\Task
{
	public function run()
	{
		$this->geek->write('');
		$this->geek->write('You are currently running:  Nerd '.V::FULL);
		$this->geek->write('');
	}

	/**
	 * {@inheritdoc}
	 */
	public function help()
	{
		return <<<HELP

Usage:
  php geek nerd.version

Runtime options:
  None available

Description:
  Find the current working version of the Nerd Library

Documentation:
  http://nerdphp.com/docs/classes/tasks/version

HELP;
	}
}