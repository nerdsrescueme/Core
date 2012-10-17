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
        foreach ($regex as $key => $file) {
            exec('php -l '.$file[0], $return);
            $message = str_replace(\Nerd\LIBRARY_PATH, '', array_pop($return));

            if (substr($message, 0, 2) === 'No') {
                continue;
            }

            $this->geek->fail();
            $errors = true;
            $this->geek->write('  - '.$message);
            $return = [];
        }

        // Write final string
        if ($errors) {
            $this->geek->write(PHP_EOL.'Errors were found, they are listed above', 'red');
        } else {
            $this->geek->write('  - No errors were found, nice job!', 'green');
        }

        $this->geek->write('');

        !$errors and $this->geek->succeed();
    }

    public function fix()
    {
        // Write first string
        $this->geek->write(PHP_EOL.'Running syntax fixer, this may take a minute'.PHP_EOL);

        // Setup iterator and variables
        $library   = $this->geek->flag('library', null);
        $directory = \Nerd\LIBRARY_PATH.($library ? DS.$library : '');
        $return    = [];
        $command   = 'php '
                   . join(DS, [\Nerd\VENDOR_PATH, 'fabpot', 'php-cs-fixer', 'php-cs-fixer']) . ' fix '
                   . $directory . ' '
                   . '--verbose --level=psr2';

        exec($command, $return);

        foreach ($return as $r) {
            $this->geek->write($r);
        }

        $this->geek->succeed();
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
  --library        # Library to traverse and check

Description:
  The Lint task can be used to check the syntax on each of
  your .php files. Primarily, this was designed to be used
  with a pre-commit hook in order to stop a commit from
  being pushed with invalid PHP syntax.

Documentation:
  http://nerdphp.com/docs/classes/tasks/lint

HELP;
    }
}
