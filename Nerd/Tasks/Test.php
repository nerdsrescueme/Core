<?php

namespace Nerd\Tasks;

class Test extends \Geek\Design\Task
{
    public function run()
    {
        $library = $this->geek->flag('library', 'nerd');
        $binary  = join(DS, [\Nerd\VENDOR_PATH, 'bin', 'phpunit']);
        $config  = join(DS, [\Nerd\LIBRARY_PATH, $library, 'phpunit.xml']);
        $command = "$binary -v -c $config";

        $this->geek->write('Running tests, please wait for results');
        $this->geek->write(' ');

        $return = [];

        exec($command, $return);

        foreach ($return as $r) {
            $this->geek->write($r);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function help()
    {
        return <<<HELP

Usage:
  php geek nerd.test [flags]

Runtime options:
  --library        # Library to test

Description:
  The test task is used to run PHPUnit test suites against
  individual libraries.

Documentation:
  http://nerdphp.com/docs/classes/tasks/test

HELP;
    }
}
