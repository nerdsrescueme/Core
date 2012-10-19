<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

// Aliasing rules
use Nerd\Model\Column
  , Nerd\Model\Constraint
  , Nerd\Datastore;

/**
 * Model Class
 *
 * The Model class acts as a base-line block for database interactions. It is built
 * to be as assuming as possible. Upon creation, subsequent models will read their
 * corresponding table metadata within the database, and assign rules and validations
 * to the data before ever creating the object itstatic. These "assumptions" are the
 * building block for all data that will be assumed from a model including forms,
 * validations, etc.
 *
 * A "traditional" model (as they're known within an ORM context) does a whole lot
 * to interface with the database, validations *only* upon performing a database
 * call, and does not do much to automate any developer interaction.
 *
 * This model class assumes a whole lot, and relies heavily on a well built database
 * schema in order to operate. It reads data types, constraints, keys and foreign key
 * relationships in order to assume what data needs to be where. Data validations are
 * performed when the data is set to the model instance *not* only when it is saved.
 * This allows us to only perform *simple* query building, leaving the heavy lifting
 * (and special features normally abstracted away!) to SQL.
 *
 * Only *basic* find capabilities are built in. Nerd believes that models are the
 * foundation of your application, and should be treated as a first class citizen,
 * not just an after-thought. Simple selects can be performed via the findBy methods,
 * but complex queries should be written in SQL.
 *
 * [!!] The model class has been written to interact with MySQL through PDO. If you
 *      wish to use another RDBMS, you must completely override or replace this model
 *      and subsequent model classes.
 *
 * @package    Nerd
 * @subpackage Core
 */
abstract class Model implements Design\Serializable, Design\Initializable
{
    use Design\Eventable
      , Design\Formattable;

public static function __initialize()
{
    static::inform();
}

    /**
     * Database connection instance
     *
     * @var Nerd\DB
     */
    protected static $connection;

    /**
     * Corresponding table name
     *
     * @var string
     */
    protected static $table;

    /**
     * Enumerable collection of Column objects
     *
     * Holds the schema definitions for each column within the model's database
     * table. It is automatically populated within the first object construction, and
     * held as static for each subsequent object.
     *
     * @see Nerd\Model\Column
     * @var Nerd\Design\Collection
     */
    protected static $columns;

    /**
     * Primary column reference
     *
     * If there is only one column marked as primary within the table schema this
     * will hold a reference link to the column within static::$columns. If there are
     * more than one primary column, it will be an array of references.
     *
     * @see Nerd\Model\Column
     * @var array|Nerd\Model\Column
     */
    protected static $primary;

    /**
     * Enumerable collection of Constraint objects
     *
     * Holds the schema definitions for each constraint in the table schema. It is
     * automatically populated within the first object construction, and held as
     * static for each subsequent object.
     *
     * @see Nerd\Model\Constraint
     * @var Nerd\Model\Collection
     */
    protected static $constraints;

    /**
     * Information present?
     *
     * If the model has loaded it's definition information then it is considered
     * "informed". If it has not, functionality of this model will be very limited
     * in terms of automation.
     *
     * @var boolean
     */
    protected static $informed = false;

    /**
     * Ignore column and constraint assumptions?
     *
     * At times you will need to turn off the automatic validations in order to
     * accomplish a task. This is used by Model::bypass() to switch assumptions on
     * and off.
     *
     * @see Nerd\Model::bypass()
     * @var boolean
     */
    protected static $ignoreAssumptions = false;

    /**
     * Last query executed
     *
     * @var array
     */
    public static $lastQuery;

    /**
     * Static constructor
     *
     * Gets metadata from the MySQL Information Schema related to this table. It then
     * parses out some of that data into enumerable arrays this class can use to do some
     * very interesting pre and post processing.
     *
     * _More explanation to come when it's finished_
     *
     * @return void
     */
    public static function inform()
    {
        // Read from cache if possible
        $dsname    = static::$table.'.model-cache';
        $datastore = Datastore::instance('file');

        if ($datastore->exists($dsname)) {
            list(self::$columns, self::$constraints, self::$primary) = $datastore->read($dsname);
            return;
        }

        //try {
            self::$columns     = new \Nerd\Design\Collection();
            self::$constraints = new \Nerd\Design\Collection();
            self::$primary     = null;

            $query =  static::connection()->prepare('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?');
            $query->execute(array(static::connection()->database, static::$table));

            while ($column = $query->fetchObject('\\Nerd\\Model\\Column')) {
                if ($column->primary) {
                    if (self::$primary === null) {
                        self::$primary = $column;
                    } else {
                        self::$primary = [self::$primary, $column];
                    }
                }

                self::$columns->add($column);
            }

            $query = static::connection()->prepare('SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?');
            $query->execute(array(static::connection()->database, static::$table));

            while ($constraint = $query->fetchObject('\\Nerd\\Model\\Constraint')) {
                self::$constraints->add($constraint);
            }

            // Cache
            $data = [self::$columns, self::$constraints, self::$primary];
            $datastore->write($dsname, $data);

            self::$informed = true;
        //} catch (\PDOException $e) {
        //    throw ne
        //}
    }

    /**
     * Get or set a connection instance for this model
     *
     * Retrieves the database connection for this model with called without any
     * arguments, otherwise it sets a new database connection and returns that one
     * instead.
     *
     * @param    string    Database connection identifier
     * @return Nerd\DB Database connection instance
     */
    public static function connection($identifier = null)
    {
        if (static::$connection === null) {
            static::$connection = DB::connection($identifier);
        }

        return static::$connection;
    }

    /**
     * Bypass assumptions
     *
     * Switches assumptions on or off depending on the value passed. If no value is
     * passed it returns the current state.
     *
     * @param    boolean|null     On/Off or None
     * @return boolean
     */
    public static function bypass($value = null)
    {
        if ($value === null) {
            return static::$ignoreAssumptions;
        }

        static::$ignoreAssumptions = $value;

        return $value;
    }

    public static function definition()
    {
        return static::$columns;
    }

    /**
     * Optimize a SQL query
     *
     * Performs string operations on a SQL query. The purpose being to optimize the
     * statements before they are sent to the server in order to increase both
     * performance and security. Be sure to only define optimizations that are global
     * in nature and sure to not interfere with a query's functionality.
     *
     * @param    string     The SQL query
     * @return string Optimized SQL query
     */
    public static function optimizeSql($sql)
    {
        !static::$informed and static::inform();

        // Replace "*" with a list of columns, reducing DB lookups
        $sql = str_replace('*', static::listColumns(), $sql);

        return $sql;
    }

    /**
     * SQL Select
     *
     * @todo  Create this function.
     */
    public static function select()
    {
        throw new \Exception('Not implemented yet');
    }

    /**
     * Magic static caller
     *
     * Provides the ability to perform "findBy" queries on this model statically. It
     * maps the information from the method call to other methods existing within the
     * model class.
     *
     * Examples:
     *
     *     Model::findOneByUsername('newuser');
     *     Model::findAllByStatus('inactive');
     *
     * @return mixed The return value of the resulting method call
     */
    public static function __callStatic($method, array $params)
    {
        !self::$informed and static::inform();
        $terms = ['find','By', 'And'];

        list($trash, $finder, $field) = explode('_', str_replace($terms, '_', $method));
        $sql = 'SELECT '.static::listColumns().' FROM `'.static::$table.'` WHERE `'.strtolower($field).'` = ?';
        ($finder == 'One' or $finder == 'First') and $sql .= ' LIMIT 1';
        array_unshift($params, $sql);

        return forward_static_call_array('self::find', $params);
    }

    /**
     * Find a record by SQL
     *
     * This function acts a router for find methods based on the SQL passed to the find
     * methods.
     *
     * @see    Model::findAll()
     * @see    Model::findOne()
     */
    public static function find()
    {
        !static::$informed and static::inform();

        if (count($params = func_get_args()) === 0) {
            throw new \InvalidArgumentException('No arguments were provided, you must at least include a SQL statement.');
        }

        return (strpos($params[0], 'LIMIT 1') !== false)
            ? forward_static_call_array('self::findOne', $params)
            : forward_static_call_array('self::findAll', $params);
    }

    /**
     * Find one record
     *
     * Performs a query against the MySQL database instance. The first argument passed
     * must be a SQL statement, followed by the subsequent parameters passed to that
     * prepared statement object.
     *
     * @return object Model object
     */
    public static function findOne()
    {
        !static::$informed and static::inform();

        $params = func_get_args();
        $sql    = static::optimizeSql(array_shift($params));

        static::$lastQuery = $sql;

        $statement = static::connection()->prepare($sql);
        $statement->execute($params);
        static::bypass(true);
            $result = $statement->fetchObject(get_called_class());
        static::bypass(false);
        $statement->closeCursor();

        $result and $result->clean();

        return $result;
    }

    /**
     * Find all records
     *
     * Performs a query against the MySQL databse instance. The first argument passed
     * must be a SQL statement, followed by the subsequent parameters passed to that
     * prepared statement.
     *
     * @return array Array of all found Model objects
     */
    public static function findAll()
    {
        !static::$informed and static::inform();

        $params = func_get_args();
        $sql    = static::optimizeSql(array_shift($params));
        $class  = get_called_class();

        static::$lastQuery = $sql;

        $statement = static::connection()->prepare($sql);
        $statement->execute($params);

        $results = [];

        static::bypass(true);

        while ($result = $statement->fetchObject($class)) {
            $result->clean();
            $results []= $result;
        }

        static::bypass(false);
        $statement->closeCursor();

        return new \Nerd\Design\Collection($results);
    }

    /**
     * List all columns on this database table
     *
     * This method accepts string arguments that correspond to database table columns,
     * The columns sent through this method will be excluded from the final column list.
     *
     * @return string Column listing
     */
    public static function listColumns()
    {
        !self::$informed and static::inform();

        $columns = [];
        $exclude = func_get_args();

        self::$columns->each(function($column) use (&$columns, &$exclude) {
            if (!in_array($column->field, $exclude)) {
                $columns []= "`$column->field`";
            }
        });

        return join(', ', $columns);
    }

    /**
     * Dirty fields
     *
     * Fields that have been changed since this instance was created.
     *
     * @var array
     */
    protected $_dirty = [];

    /**
     * Errors
     *
     * @var array
     */
    public $errors = [];

    /**
     * Field values
     *
     * @var array
     */
    protected $_values = [];


    /**
     * Perform a SQL Delete
     *
     * Creates and performs a SQL delete against this model instance. It will only
     * perform a delete if there is a primary key defined, and that primary key has
     * a value. This way, you will never accidentally delete data by creating a
     * delete statement that acts globally.
     *
     * @throws \Nerd\DB\Exception If no primary key is defined
     * @throws \Nerd\DB\Exception If the query cannot be executed
     *
     * @return array Common SQL result array
     */
    public function delete()
    {
        if (static::$primary === null) {
            throw new \Nerd\DB\Exception('A primaty key must be defined in order to create a delete query.');
        }

        $sql   = 'DELETE FROM `'.static::$table.'`';

        if (is_array(static::$primary)) {
            $sql .= ' WHERE '.static::$primary[0]->field.' = :'.static::$primary[0]->field;
            $sql .= ' AND '.static::$primary[1]->field.' = :'.static::$primary[1]->field;
            $params = array(
                ':'.static::$primary[0]->field => $this->{static::$primary[0]->field},
                ':'.static::$primary[1]->field => $this->{static::$primary[1]->field},
            );
        } else {
            $sql .= ' WHERE '.static::$primary->field.' = :'.static::$primary->field;
            $params = array(
                ':'.static::$primary->field => $this->{static::$primary->field},
            );
        }

        static::$lastQuery = $sql;

        try {
            $statement = static::connection()->prepare($sql);
            $success   = $statement->execute($params);
        } catch (\PDOException $e) {
            throw new \Nerd\DB\Exception($e);
        }

        return [$success, $statement->rowCount(), $statement];
    }

    /**
     * Perform a SQL Replace
     *
     * @see    Model::insert();
     */
    public function replace()
    {
        $this->insert(true);
    }

    /**
     * Perform a SQL Insert
     *
     * Creates and performs a SQL insert for this model instance. For an insert all
     * columns are listed with their corresponding values.
     *
     * @throws \Nerd\DB\Exception If the query cannot be executed
     *
     * @return array Common SQL result array
     */
    public function insert($replace = false)
    {
        $model   = $this;
        $params  = [];

        self::$columns->each(function($column) use (&$model, &$params) {
            $params[':'.$column->field] = isset($model->_values[$column->field]) ? $model->_values[$column->field] : null;
        });

        $sql = 'INSERT INTO '
               . '`'.static::$table.'` '
               . '('.static::listColumns().') '
               . 'VALUES ('
               . join(', ', array_keys($params))
               . ');';

        if ($replace) {
            $sql = str_replace('INSERT INTO', 'REPLACE INTO', $sql);
        }

        static::$lastQuery = $sql;

        try {
            $statement = static::connection()->prepare($sql);
            $success   = $statement->execute($params);
        } catch (\PDOException $e) {
            throw new \Nerd\DB\Exception($e);
        }

        return [$success, $statement->rowCount(), $statement];
    }

    /**
     * Perform a SQL Update
     *
     * Creates and performs a SQL update against this model instance. It will only
     * perform an update if there is a primary key defined, and that primary key has
     * a value. This way, you will never accidentally update all data by creating an
     * update statement that acts globally.
     *
     * @throws \Nerd\DB\Exception If no primary key is defined
     * @throws \Nerd\DB\Exception If the query cannot be executed
     *
     * @return array Common SQL result array
     */
    public function update()
    {
        if (static::$primary === null) {
            throw new \Nerd\DB\Exception('A primary key must be defined in order to create an update query.');
        }

        $params  = [];
        $columns = [];

        foreach ($this->_dirty as $field) {
            $columns []= "`$field` = :$field";
            $params[':'.$field] = $this->{$field};
        }

        $sql = 'UPDATE '
             . '`'.static::$table.'` '
             . 'SET '
             . join(', ', $columns);

        if (is_array(self::$primary)) {
            $sql .= ' WHERE `'.self::$primary[0]->field.'` = :pk'.self::$primary[0]->field;
            $sql .= ' AND `'.self::$primary[1]->field.'` = :pk'.self::$primary[1]->field;

            $params[':pk'.self::$primary[0]->field] = $this->{self::$primary[0]->field};
            $params[':pk'.self::$primary[1]->field] = $this->{self::$primary[1]->field};
        } else {
            $sql .= ' WHERE `'.self::$primary->field.'` = :pk'.self::$primary->field;
            $params[':pk'.self::$primary->field] = $this->{self::$primary->field};
        }

        self::$lastQuery = $sql;

        try {
            $statement = static::connection()->prepare($sql);
            $success   = $statement->execute($params);
        } catch (\PDOException $e) {
            throw new \Nerd\DB\Exception($e);
        }

        return [$success, $statement->rowCount(), $statement];
    }

    /**
     * Mark all columns as clean data
     *
     * This will make it so methods that need to know what data has been changed on this
     * model instance will not register any of the columns as "dirty"
     *
     * @return void
     */
    public function clean()
    {
        $this->_dirty = [];
    }

    /**
     * Provides the most *basic* form for a given model. This doesn't do much to figure
     * out what fields to use, and where to put them, but it does provide _very_ basic
     * scaffolding to get you up an running.
     *
     * [!!] In most instances you will wish to overload this method.
     *
     * @param    \Nerd\Form      Form instance to attach fields to, if null one is created
     * @return \Nerd\Form Form instance
     */
    public function form(Form $form = null)
    {
        $form === null and $form = (new Form())->method('post');

        $class    = strtolower(Autoloader::denamespace(get_class($this)));
        $fieldset = $form->fieldset();

        self::$columns->each(function($column) use (&$form, &$fieldset, $class) {
            $type = 'text';

            if ($column->is(Column::TYPE_DATE)) {
                $type = "datetime";
            } elseif ($column->is(Column::TYPE_TEXT)) {
                $type = 'textarea';
            } elseif ($column->is(Column::TYPE_TINYINT) and $column->constraint == 1) {
                $type = 'select';
            } elseif ($column->is(Column::TYPE_NUMBER)) {
                $type = 'number';
            } elseif ($column->is(Column::TYPE_ENUM)) {
                $type = 'select';
            }

            if ($column->automatic) {
                $type = 'hidden';
            }

            $field = $form->field($type, [
                'name'  => "{$class}[{$column->field}]",
                'id'    => "{$class}_{$column->field}",
            ], null);

            isset($this->_values[$column->field]) and $field->value($this->_values[$column->field]);

            if ($type === 'select') {
                if ($column->constraint == 1) {
                    $field->options = [0 => 'false', 1 => 'true'];
                } else {
                    $field->options  = $column->options();
                }

                if (isset($this->_values[$column->field])) {
                    $field->selected = $this->_values[$column->field];
                }
            }

            $field->label(Str::humanize($column->field));

            if ($column->automatic) {
                $field->wrap(false)->wrapField(false);
                $field->label = null;
            }

            if ($type === 'text') {
                $field->maxlength($column->constraint);
            }

            if (!$column->nullable and !$type === 'datetime') {
                $field->required(true);
            }

            $fieldset->field($field);
        });

        return $form;
    }

    /**
     * Model has errors?
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep()
    {
        return $this->_values;
    }

    /**
     * Magic setter
     *
     * Sets data on this model instance. First, it will look for the column in the model
     * definition arrays. Afterwards it will use that definition to modify that data,
     * run validation checks against it, set it as a "dirty" column, and finally set the
     * value. If bypass() is enabled, then no checks, modifications or validations are
     * performed except to see if the column exists on this model.
     *
     * @throws \InvalidArgumentException If the column does not exist
     *
     * @param    string    Table column name
     * @param    string    Value to set on column
     * @return void
     */
    public function __set($property, $value)
    {
        $column = self::$columns->find(function($column) use ($property) {
            return $column->field == $property;
        });

        if ($column === null) {
            throw new \InvalidArgumentException("Property [$property] does not exist on ".get_class($this));
        }

        if (static::bypass()) {
            array_push($this->_dirty, $property);
            $this->_values[$property] = $value;
        } else {
            $value = $column->modify($value);

            if ($column->assume($this, $value)) {
                array_push($this->_dirty, $property);
                $this->_values[$property] = $value;
            }
        }
    }

    public function __get($property)
    {
        if (isset($this->_values[$property])) {
            return $this->_values[$property];
        }
    }

    public function __isset($property)
    {
        return isset($this->_values[$property]);
    }
}
