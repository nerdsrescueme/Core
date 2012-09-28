<?php

namespace Nerd\Tasks;

class Generate extends \Geek\Design\Task
{
	public function run()
	{
		$this->geek->write($this->help());
	}

	public function klass()
	{
		$library = $this->geek->flag('library', 'application');
		$class   = $this->geek->flag('name', 'newclass');
		$file    = LIBRARY_PATH.DS.$library.DS.'classes'.DS.$class.'.php';

		$class = ucfirst($class) and $library = ucfirst($library);

		$this->geek->write("Creating a new class '$class' in $library library...");

		$template = <<<TEMP
<?php

namespace $library;

class $class
{
	public function __construct()
	{
		
	}
}
TEMP;

		if (file_put_contents($file, $template))
		{
			$this->geek->write('File written successfully', 'green');
		}
		else
		{
			$this->geek->write('File could not be written', 'red');
		}
	}

	public function help()
	{
		return 'Write some help!';
	}
}