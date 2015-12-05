<?php

namespace Opeyemiabiodun\PotatoORM\Models;

use Exception;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

class Model
{
    protected $_attributes = [];

	protected $_connection;

    protected $_primaryKey;

    protected $_table;

    public function __get($property)
    {
        if (array_key_exists($property, $_attributes))
        {
            return $_attributes[$property];
        }
    }

    public function __set($property, $value)
    {
        if (array_key_exists($property, $_attributes))
        {
            $_attributes[$property] = $value;
        }
    }

    public static function destroy($number)
    {
        return $this->_connection->deleteRecord($this->_table, $number - 1);
    }

    public static function find($number)
    {
        if ($number <= 0) {
            throw new Exception("Error Processing Request", 1);            
        }

        return $this->_connection->findRecord($this->_table, $number - 1);
    }

    public static function getAll()
    {
        return $this->_connection->getAllRecords($this->_table);
    }

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

    public function save()
    {
        if (is_null($this->_connection)) {
            throw new Exception("Error Processing Request", 1);
        }

        if (is_null($this->_table)) {
            throw new Exception("Error Processing Request", 1);
        }

        if (! $this->hasAttributes()) {
            throw new Exception("Error Processing Request", 1);
        }

        return $this->_connection->createRecord($this->_table, $this->_attributes);
    }

    public static function setConnection(Connection $connection)
    {
        $this->_connection = $connection;
    }

    public static function setTable($table)
    {
        $this->_table = $table;

        $columns = $this->_connection->getColumns($table);
        for ($i=0; $i < count($columns); $i++) { 
            array_push($this->_attributes, $columns[i][key($columns[i])]);
        }

        $this->_primaryKey = $this->_connection->getPrimaryKey($table);
    }
}






