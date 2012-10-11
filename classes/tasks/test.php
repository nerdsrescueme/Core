<?php

namespace Nerd\Tasks;

class Test extends \Geek\Design\Task
{
	public function run()
	{
		$library = $this->geek->flag('library', 'nerd');
		$command = \Nerd\VENDOR_PATH . DS . 'bin' . DS . 'phpunit -v -c '
		         . join(DS, [\Nerd\LIBRARY_PATH, $library, 'phpunit.xml']);

		$this->geek->write('Running tests, please wait for results');
		$this->geek->write(' ');

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