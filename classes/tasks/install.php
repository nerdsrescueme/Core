<?php

namespace Nerd\Tasks;

class Install extends \Geek\Design\Task
{
	public function run()
	{
		$this->geek->write('Starting install task...', 'green');
		$this->geek->write('Finished install task, all done!', 'green');
	}

	public function application()
	{
		$library = $this->geek->flag('library', 'application');

		$this->geek->write("Starting install task on $library library...", 'green');

			$this->geek->write_nobreak('  Making storage folders writable... ');

			if (chmod(LIBRARY_PATH.DS.$library.DS.'storage', 0777))
			{
				$this->geek->write('Success', 'green');
			}
			else
			{
				$this->geek->write('Failed', 'red');
			}

		$this->geek->write('Finished install task, all done!', 'green');
	}

	public function help()
	{
		return 'Write some help!';
	}
}