<?php

namespace Nerd\Tasks;

class String extends \Geek\Design\Task
{
    public function run()
    {
        $this->geek->write($this->help());
    }

    public function distance()
    {
        list($t, $c1, $c2) = $this->geek->args();

        $case     = $this->geek->flag('case', false);
        $distance = \Nerd\Str\Compare::levenshtein($c1, $c2, $case);

        $this->geek->write('');
        $this->geek->write("Distance between '$c1' and '$c2' is: $distance");
        $this->geek->write('');
    }

    public function random()
    {
        $length  = $this->geek->flag('length', 32);
        $charset = $this->geek->flag('charset', 'alnumsym');

        $this->geek->write('');
        $this->geek->write(\Nerd\Str::random($length, $charset));
        $this->geek->write('');
    }

    public function password()
    {
        list($t, $password) = $this->geek->args();

        $this->geek->write('');
        $this->geek->write('Encrypted password: '.md5($password));
        $this->geek->write('');
    }

    /**
     * {@inheritdoc}
     */
    public function help()
    {
        return <<<HELP

Usage:
  php geek nerd.string.random [flags]

Runtime options:
  --length          # Library to traverse and check
  --charset         # Character pool (see Str::pool)

Description:
  The string task performs a few different functions. All
  functions will be related to generating or manipulating
  string values in the console.

Documentation:
  http://nerdphp.com/docs/classes/tasks/string

HELP;
    }
}
