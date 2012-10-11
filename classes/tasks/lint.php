<?php

namespace Nerd\Tasks;

class Lint extends \Geek\Design\Task
{
	public function run()
	{
		// Write first string
		$this->geek->write(PHP_EOL.'Running syntax check, this may take a minute'.PHP_EOL);

		// Setup iterator and variables
		$library   = $this->geek->flag('library', null);
		$directory = new \RecursiveDirectoryIterator(\Nerd\LIBRARY_PATH.($library ? DS.$library : ''));
		$iterator  = new \RecursiveIteratorIterator($directory);
		$regex     = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
		$return    = [];
		$errors    = false;

		// Loop through .php files, and output a message on error
		foreach($regex as $key => $file)
		{
			exec('php -l '.$file[0], $return);
			$message = str_replace(\Nerd\LIBRARY_PATH, '', array_pop($return));

			if (substr($message, 0, 2) === 'No')
			{
				continue;
			}

			$errors = true;
			$this->geek->write('  - '.$message);
			$return = [];
		}

		// Write final string
		if ($errors)
		{
			$this->geek->write(PHP_EOL.'Errors were found, they are listed above', 'red');
		}
		else
		{
			$this->geek->write('  - No errors were found, nice job!', 'green');
		}

		$this->geek->write('');
	}

	public function help()
	{
		return 'Write some help!';
	}
}