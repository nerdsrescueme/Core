<?php

/**
 * Namespace reserved for Geek tasks
 *
 * @package    Geek
 * @subpackage Tasks
 */
namespace Nerd\Tasks;

/**
 * Help display task
 *
 * @package    Geek
 * @subpackage Tasks
 */
class Help extends \Geek\Design\Task
{
    /**
     * Default method
     *
     * Outputs the help info for ion
     *
     * Usage: php ion help
     *
     * @return void
     */
    public function run()
    {
        if (count($this->geek->args) === 0) {
            $this->geek->write($this->format($this->help()), 'white');

            return;
        }

        // Resolve class help to show.
        $path = explode('.', $this->geek->arg(1));
        // Allow help per method… not impelmented yet.
        //

        $class = '\\'.ucfirst($path[0]).'\\Tasks\\'.ucfirst($path[1]);
        $class = new $class();

        $text = $class->help();

        if (!empty($text)) {
            $this->geek->write($this->format($text), 'white');
        }
    }

    /**
     * Help
     *
     * Usage: php geek help
     *
     * @return boolean
     */
    public function help()
    {
        return <<<HELP

Default task usage:
  php geek task[.method] [flags] [args]

Namespaced task usage:
  php geek namespace.task[.method] [flags] [args]

Finding task help:
  php geek help [namespace.]task

Description:
  Geek can be used to ease development tasks within the Nerd framework. Custom
  tasks can be developed with ease, or simple use the bundled Nerd tasks.

Documentation:
  http://nerdphp.com

HELP;
    }

    /**
     * Format help output
     *
     * @param    string     Help text
     * @return string Formatted help text
     */
    private function format($input = '')
    {
        return str_replace("\n", "\n    ", $input);
    }
}
