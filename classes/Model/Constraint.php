<?php

namespace Nerd\Model;

class Constraint
{
    const PRIMARY = 2;
    const UNIQUE  = 4;
    const FOREIGN = 8;

    public $name;
    public $type;
    public $relation;

    public function __construct()
    {
        $this->name = $this->CONSTRAINT_NAME;

        switch ($this->CONSTRAINT_TYPE) {
            case 'PRIMARY KEY' :
                $this->type = static::PRIMARY;
                break;
            case 'UNIQUE' :
                $this->type = static::UNIQUE;
                break;
            case 'FOREIGN KEY' :
                $this->type = static::FOREIGN;

                $parts = explode('-', $this->name);

                $this->relation = array(
                    'from'    => $parts[0],
                    'keyFrom' => $parts[1],
                    'to'      => $parts[2],
                    'keyTo'   => $parts[3],
                );

                break;
        }

        $fields = array(
            'CONSTRAINT_CATALOG',
            'CONSTRAINT_SCHEMA',
            'CONSTRAINT_NAME',
            'TABLE_SCHEMA',
            'TABLE_NAME',
            'CONSTRAINT_TYPE'
        );

        foreach ($fields as $field) {
            unset($this->{$field});
        }
    }

    public function is($type)
    {
        return $this->type === $type;
    }
}
