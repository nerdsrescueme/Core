<?php

namespace Nerd\Tasks;

class Test extends \Geek\Design\Task
{
	public function run()
	{
		$command = \Nerd\VENDOR_PATH . DS . 'bin' . DS . 'phpunit -c '
						 . join(DS, [\Nerd\LIBRARY_PATH, 'nerd', 'phpunit.xml']);

		$this->geek->write('Running tests, please wait for results…');

		$return = [];

		exec($command, $return);

		foreach($return as $r)
		{
			$this->geek->write($r);
		}
	}

	public function help()
	{
		return 'Write some help!';
	}
}