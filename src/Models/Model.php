<?php

namespace Opeyemiabiodun\PotatoORM\Models;

use Exception;
use Opeyemiabiodun\PotatoORM\Connections\Connection;
use Opeyemiabiodun\PotatoORM\Connections\PgSqlConnection;

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
    protected $_connection;

    /**
     * The primary key of the model's database table.
     *
     * @var string
     */
    protected $_primaryKey;

    /**
     * The model's database table.
     *
     * @var string
     */
    protected $_table;

    /**
     * The model's constructor method.
     *
     * @param Connection|null $connection An Opeyemiabiodun\PotatoORM\Connections\Connection instance or null
     * @param string          $table      The name of the model's table in the database
     */
    public function __construct(Connection $connection = null, $table = null)
    {
        if (is_null($connection)) {
            $this->setConnection(new PgSqlConnection());
        } else {
            $this->setConnection($connection);
        }

        if (is_null($table)) {
            $this->setTable(get_class($this).'-table');
        } else {
            $this->setTable($table);
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
        if (array_key_exists($property, $_attributes)) {
            return $_attributes[$property];
        } else {
            throw new Exception('Error Processing Request', 1);
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
        if (!is_scalar($value)) {
            throw new Exception('Error Processing Request', 1);
        }

        if (array_key_exists($property, $_attributes)) {
            $_attributes[$property] = $value;
        } else {
            throw new Exception('Error Processing Request', 1);
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
        if ($number <= 0) {
            throw new Exception('Error Processing Request', 1);
        }

        return $this->_connection->deleteRecord($this->_table, $number - 1);
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
        if ($number <= 0) {
            throw new Exception('Error Processing Request', 1);
        }

        return $this->_connection->findRecord($this->_table, $number - 1);
    }

    /**
     * Returns all instances of the model in the database.
     *
     * @return array All instances of the model in the database.
     */
    public static function getAll()
    {
        return $this->_connection->getAllRecords($this->_table);
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
            if (!is_null($value)) {
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
        if (is_null($this->_connection)) {
            throw new Exception('Error Processing Request', 1);
        }

        if (is_null($this->_table)) {
            throw new Exception('Error Processing Request', 1);
        }

        if (!$this->hasAttributes()) {
            throw new Exception('Error Processing Request', 1);
        }

        if (empty($this->_connection->findRecord($this->_table, $this->_attributes[getPrimaryKey($this->_table)]))) {
            return $this->_connection->createRecord($this->_table, $this->_attributes);
        } else {
            return $this->_connection->updateRecord($this->_table, $this->_attributes);
        }
    }

    /**
     * Sets the model's connection.
     *
     * @param Connection $connection An instance of Opeyemiabiodun\PotatoORM\Connections\Connection.
     */
    protected static function setConnection(Connection $connection)
    {
        $this->_connection = $connection;
    }

    /**
     * Sets the model's table.
     *
     * @param string $table An existing table in the database.
     */
    protected static function setTable($table)
    {
        if (gettype($table) !== 'string') {
            throw new Exception('Error Processing Request', 1);
        }

        $this->_table = $table;

        $columns = $this->_connection->getColumns($table);
        for ($i = 0; $i < count($columns); $i++) {
            array_push($this->_attributes, $columns[i][key($columns[i])]);
        }

        $this->_primaryKey = $this->_connection->getPrimaryKey($table);
    }
}
