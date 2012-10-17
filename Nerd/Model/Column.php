<?php

namespace Nerd\Model;

// Aliasing rules
use \Nerd\Model;

class Column
{
    const KEY_PRIMARY = 'PRI';
    const KEY_UNIQUE  = 'UNI';
    const KEY_MULTI   = 'MUL';

    const TYPE_CHAR = 'char';
    const TYPE_VARCHAR = 'varchar';
    const TYPE_TEXT = 'text';
    const TYPE_ENUM = 'enum';
    const TYPE_INT = 'int';
    const TYPE_TINYINT = 'tinyint';
    const TYPE_DOUBLE = 'double';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';

    const TYPE_STRING = 'char varchar text enum';
    const TYPE_NUMBER = 'int tinyint double';
    const TYPE_DATE   = 'datetime timestamp';

    const NULLABLE = 'YES';
    const NOT_NULLABLE = 'NO';

    public $assumptions;
    public $errors = [];
    public $field;
    public $type;
    public $constraint;
    public $nullable = false;
    public $index;
    public $default;
    public $extra;
    public $comment;
    public $primary = false;
    public $unique = false;
    public $multiple = false;
    public $unsigned = false;
    public $zerofill = false;
    public $automatic = false;

    private $_datatype;

    public function __construct()
    {
        $this->field    = $this->COLUMN_NAME;
        $this->type     = $this->DATA_TYPE;
        $this->nullable = $this->IS_NULLABLE === static::NULLABLE;
        $this->default  = $this->COLUMN_DEFAULT;
        $this->extra    = $this->EXTRA;
        $this->comment  = $this->COLUMN_COMMENT;

        $this->assumptions = new \Nerd\Design\Collection([]);

        preg_match("/([a-z]+)(\(([A-Za-z0-9,\']+)\))?\s?(unsigned)?\s?(zerofill)?/", $this->COLUMN_TYPE, $parts);
		isset($parts[1]) and $this->type = $parts[1];
		isset($parts[3]) and $this->constraint = $parts[3];
		isset($parts[5]) and $this->unsigned = true;
		isset($parts[6]) and $this->zerofill = true;

        $this->unsigned !== null and $this->unsigned = true;
        $this->zerofill !== null and $this->zerofill = true;

        if (strpos($this->extra, 'auto_increment') !== false) {
            $this->automatic = true;
        }

        switch ($this->COLUMN_KEY) {
            case static::KEY_PRIMARY :
                $this->primary = true;
                break;
            case static::KEY_UNIQUE :
                $this->unique = true;
                break;
            case static::KEY_MULTI :
                // DETERMINE!!!
                $this->multiple = true;
                break;
        }

        if ($this->is(static::TYPE_STRING)) {
            $this->_assignStringAssumptions();
        } elseif ($this->is(static::TYPE_NUMBER)) {
            $this->_assignNumberAssumptions();
        } elseif ($this->is(static::TYPE_DATE)) {
            $this->_assignDateAssumptions();
        } else {
            throw new \InvalidArgumentException("Database column type [$this->type] is not defined, please define it.");
        }

        $this->_assignGlobalAssumptions($this->_parseComment());

        $fields = array(
            'TABLE_CATALOG',
            'TABLE_SCHEMA',
            'TABLE_NAME',
            'COLUMN_NAME',
            'ORDINAL_POSITION',
            'COLUMN_DEFAULT',
            'IS_NULLABLE',
            'DATA_TYPE',
            'CHARACTER_MAXIMUM_LENGTH',
            'CHARACTER_OCTET_LENGTH',
            'NUMERIC_PRECISION',
            'NUMERIC_SCALE',
            'CHARACTER_SET_NAME',
            'COLLATION_NAME',
            'COLUMN_TYPE',
            'COLUMN_KEY',
            'EXTRA',
            'PRIVILEGES',
            'COLUMN_COMMENT',
        );

        foreach ($fields as $field) {
            unset($this->{$field});
        }
    }

    public function assume(Model $model, $value)
    {
        $success = true;

        $this->assumptions->each(function($assumption) use (&$success, $model, $value) {
            $s = $assumption->check($value);

            if ($s === false) {
                if (!isset($model->errors[$assumption->column->field])) {
                    $model->errors[$assumption->column->field] = [];
                }

                $model->errors[$assumption->column->field][]= sprintf($assumption->errorText(), $assumption->column->field, $value);
            }

            if ($success and !$s) {
                $success = false;
            }
        });

        return $success;
    }

    public function modify($value)
    {
        foreach ($this->assumptions as $assumption) {
            $value = $assumption->modify($value);
        }

        return $value;
    }

    public function is($type)
    {
        return $type === $this->type or strpos($type, $this->type) !== false;
    }

    public function options()
    {
        if (!$this->is(static::TYPE_ENUM)) {
            return [];
        }

        $keys  = array_map(function($v) { return str_replace("'", '', $v); }, explode(',', $this->constraint));
        $vals  = array_map(function($v) { return ucfirst($v); }, $keys);

        return array_combine($keys, $vals);
    }

    private function _assignGlobalAssumptions(array $extras = [])
    {
        if (!$this->nullable) {
            $this->assumptions->add(new Assumption\Required($this));
        }

        foreach ($extras as $extra) {
            $assumption = '\\Nerd\\Model\\Assumption\\'.ucfirst($extra[0]);

            // If assumption doesn't exist within the library, use the current
            // application's namespace.
            if (!class_exists($assumption, false)) {
                $assumption = str_replace('Nerd', ucfirst(\Nerd\APPLICATION_NS), $assumption);
            }

            if (class_exists($assumption, false)) {
                if (count($extra) > 1) {
                    $this->assumptions->add(new $assumption($this, $extra[1]));
                } else {
                    $this->assumptions->add(new $assumption($this));
                }
            }
        }
    }

    private function _assignStringAssumptions()
    {
        if ($this->type === static::TYPE_ENUM) {
            $this->assumptions->add(new Assumption\Options($this));

            return;
        }

        $this->assumptions->add(new Assumption\Max($this, $this->constraint));
    }

    private function _assignNumberAssumptions()
    {
        if ($this->type == static::TYPE_TINYINT and $this->constraint == 1) {
            $this->assumptions->add(new Assumption\Binary($this));

            return;
        }

        $this->assumptions->add(new Assumption\Number($this));
    }

    private function _assignDateAssumptions()
    {
        // DateTime Assumptions
    }

    private function _parseComment()
    {
        if (strlen($this->comment) === 0) {
            return [];
        }

        $parts  = explode(',', $this->comment);
        $extras = [];

        foreach ($parts as $part) {
            if (strpos($part, '(')) {
                @list($assumption, $constraint) = explode('(', substr($part, 0, -1));
                $extras []= array($assumption, $constraint);
            } else {
                $extras [] = array($part);
            }
        }

        return $extras;
    }
}
