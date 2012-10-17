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
  php geek nerd.lint [flags]

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
