<?php

namespace Opeyemiabiodun\PotatoORM\Models;

use RuntimeException;
use InvalidArgumentException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;
use Opeyemiabiodun\PotatoORM\Connections\PgSqlConnection;
use Opeyemiabiodun\PotatoORM\Exceptions\AssignmentException;
use Opeyemiabiodun\PotatoORM\Exceptions\PropertyNotFoundException;

trait Model
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $_attributes = [];

    /**
     * The model's database connection.
     *
     * @var Opeyemiabiodun\PotatoORM\Connections\Connection
     */
    protected static $_connection;

    /**
     * The primary key of the model's database table.
     *
     * @var string
     */
    protected static $_primaryKey;

    /**
     * The model's database table.
     *
     * @var string
     */
    protected static $_table;

    /**
     * The model's constructor method.
     *
     * @param Connection|null $connection An Opeyemiabiodun\PotatoORM\Connections\Connection instance or null
     * @param string          $table      The name of the model's table in the database
     */
    public function __construct($array = [], Connection $connection = null, $table = null)
    {
        if (null === $connection) {
            $connection = PgSqlConnection::load();
        }

        if (null === $table) {
            $table = strtolower(substr(get_class($this),strripos(get_class($this), "\\") + 1))."_table";
        }

        $this->setConnection($connection);
        $this->setTable($table);

        if (! empty($array)) {
            $this->setProperties($array);
        }
    }

    /**
     * The getter method for the model's properties.
     *
     * @param string $property The particular property
     *
     * @return int|float|string|bool The value of the property
     */
    public function __get($property)
    {
        if (array_key_exists($property, $this->_attributes)) {
            return $this->_attributes[$property];
        } else {
            throw new PropertyNotFoundException("The ".get_class($this)." instance has no {$property} property.");
        }
    }

    /**
     * The setter method for the model's properties.
     *
     * @param string                $property The particular property
     * @param int|float|string|bool $value    The value of the property
     */
    public function __set($property, $value)
    {
        if (! is_scalar($value)) {
            throw new AssignmentException("Only scalar values can be assigned to the {$property} property.");
        }

        if (array_key_exists($property, $this->_attributes)) {
            $this->_attributes[$property] = $value;
        } else {
            throw new PropertyNotFoundException("The ".get_class($this)." instance has no {$property} property.");
        }
    }

    /**
     * Deletes a specified instance of the model in the database.
     *
     * @param int $number Specifies which model instance to delete; the 1st, 2nd, 3rd, .....
     *
     * @return bool Returns boolean true if the instance was successfully deleted or else it returns false.
     */
    public static function destroy($number)
    {
        if (! is_int($number)) {
            throw new InvalidArgumentException("The parameter {$number} is not an integer. An integer is required instead.");
        }

        if ($number <= 0) {
            throw new InvalidArgumentException("The parameter {$number} is not a positive integer. A positive integer is required instead.");
        }

        return self::$_connection->deleteRecord(self::$_table, $number - 1);
    }

    /**
     * Finds a specified instance of the model in the database.
     *
     * @param int $number Specifies which model instance to find; the 1st, 2nd, 3rd, .....
     *
     * @return array Returns the particular instance of the model.
     */
    public static function find($number)
    {
        if (! is_int($number)) {
            throw new InvalidArgumentException("The parameter {$number} is not an integer. An integer is required instead.");
        }

        if ($number <= 0) {
            throw new InvalidArgumentException("The parameter {$number} is not a positive integer. A positive integer is required instead.");
        }

        $result = new self();
        
        $record = self::$_connection->findRecord(self::$_table, $number - 1);

        $result->setProperties($record[0]);
        
        return $result;
    }

    /**
     * Returns all instances of the model in the database.
     *
     * @return array All instances of the model in the database.
     */
    public static function getAll()
    {
        $records = self::$_connection->getAllRecords(self::$_table);

        $count = count($records);
        $result = [];

        for ($i=0; $i < $count ; $i++) { 
            array_push($result, new self($records[$i]));
        }

        return $result;
    }

    /**
     * Checks the attributes of the model to ensure they are not all null.
     *
     * @return bool true if at least one of the models's attributes is not null else false.
     */
    private function hasAttributes()
    {
        $hasAttributes = false;

        foreach ($this->_attributes as $key => $value) {
            if (! is_null($value)) {
                $hasAttributes = true;
            }
        }

        return $hasAttributes;
    }

    /**
     * Saves or updates an instance of the model in the database.
     *
     * @return bool Returns true if the operation was successfully else returns false.
     */
    public function save()
    {
        if (! $this->hasAttributes()) {
            throw new RuntimeException(get_class($this)." model has nothing to persist to the database.");
        }

        $pk = (empty($this->_attributes[self::$_primaryKey])) ? "NULL" :  $this->_attributes[self::$_primaryKey];

        #$record = self::$_connection->findRecord(self::$_table, (string) $pk);

        if ("NULL" === $pk) {
            return self::$_connection->createRecord(self::$_table, $this->_attributes);
        } else {
            return self::$_connection->updateRecord(self::$_table, (string) $pk, $this->_attributes);
        }
    }

    /**
     * Sets the model's connection.
     *
     * @param Opeyemiabiodun\PotatoORM\Connections\Connection $connection An instance of Opeyemiabiodun\PotatoORM\Connections\Connection.
     */
    protected function setConnection(Connection $connection)
    {
        self::$_connection = $connection;
    }

    /**
     * Sets the model's properties.
     *
     * @param string $array An array containing the model's poperties.
     */
    public function setProperties($array)
    {
        foreach ($this->_attributes as $key => $value) {

            if (! empty($array[$key])) {
                $this->_attributes[$key] = $array[$key];
            }

        }
    }

    /**
     * Sets the model's table.
     *
     * @param string $table An existing table in the database.
     */
    protected function setTable($table)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        self::$_table = $table;

        $columns = self::$_connection->getColumns($table);

        for ($i = 0; $i < count($columns); $i++) {
            $this->_attributes[$columns[$i]["column_name"]] = null;
        }

        self::$_primaryKey = self::$_connection->getPrimaryKey($table);
    }
}
