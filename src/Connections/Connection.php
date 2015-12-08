<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Dotenv\Dotenv;
use Exception;
use RuntimeException;

abstract class Connection
{
    /**
     * $_database The database name.
     * @var string
     */
    protected $_database;

    /**
     * $_host The host name.
     * @var string
     */
    protected $_host;

    /**
     * $_password The password to the database server.
     * @var string
     */
    protected $_password;

    /**
     * $_pdo The PDO instance of the connection.
     * @var PDO
     */
    protected $_pdo;

    /**
     *  $_port The port number to the database server.
     * @var string
     */
    protected $_port;

    /**
     * The username to the database server.
     * @var string
     */
    protected $_username;

    /**
     * The Connection constructor
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * The method called in the constructor.
     * @return void
     */
    abstract protected function connect();

    /**
     * Returns the Connection's PDO.
     * @return PDO  PHP Data Objects
     */
    public function getPdo()
    {
        return $this->_pdo;
    }

    /**
     * Loads variables in the .env file.
     * @return void
     */
    protected function loadDbEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/../..');
        $dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])->notEmpty();
        $dotenv->required(['DB_PORT']);
        $dotenv->load();

        $this->_host = getenv('DB_HOST');
        $this->_database = getenv('DB_DATABASE');
        $this->_username = getenv('DB_USERNAME');
        $this->_password = getenv('DB_PASSWORD');
        if (isset(getenv('DB_PORT'))) {
            $this->_port = getenv('DB_PORT');            
        }
    }

    /**
     * Loads variables in the .env file and handles exceptions.
     * @return void
     */
    protected function useDbEnv()
    {
        try {
            $this->loadDbEnv();
        } catch (RuntimeException $e) {
        } catch (Exception $e) {
        }
    }

    /**
     * Creates a record in the database.
     * @param  string   $table  The table where the a new record is made.
     * @param  array    $record The record to be made in the database.
     * @return bool
     */
    public function createRecord($table, $record)
    {
        if (gettype($table) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        if (gettype($record) !== 'array') {
            throw new Exception("Error Processing Request", 1);            
        } 

        $count = count($record);

        $sql = "INSERT INTO {$table} (";
        foreach ($record as $key => $value) {
            if ($count > 1) {
                $sql = $sql."{$key}, ";
            } else {
                $sql = $sql."{$key}) ";
            }
            $count--;
        }

        $count = count($record);

        $sql .= "VALUES (";
        foreach ($record as $key => $value) {
            if ($count > 1) {
                $sql = $sql."{$value}, ";
            } else {
                $sql = $sql."{$value}) ";
            }
            $count--;
        }

        return $this->getPdo()->prepare($sql)->execute();
    }

    /**
     * Remove a record in the database.
     * @param  string $table The table where the record is removed in the database.
     * @param  string $pk    The primary key value of the record.
     * @return bool          Returns boolean true if the record was successfully deleted or else it returns false.
     */
    public function deleteRecord($table, $pk)
    {
        if (gettype($table) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        if (gettype($pk) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        return $this->getPdo()->prepare("DELETE FROM {$table}
                                            WHERE {$this->getPrimaryKey($table)}={$pk}")->execute();
    }

    /**
     * Returns a particular record in a table.
     * @param  string $table The table of the record. 
     * @param  string $pk    The primary key value of the record.
     * @return array         An array containing the particular record.
     */
    public function findRecord($table, $pk)
    {
        if (gettype($table) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        if (gettype($pk) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        }

        return $this->getPdo()->query("SELECT * FROM {$table}
                                        WHERE {$this->getPrimaryKey($table)}={$pk}")->fetchAll();
    }

    /**
     * Returns all the records in a table.
     * @param  string $table The table inspected for all its records.
     * @return array         All the records in the table.        
     */
    public function getAllRecords($table)
    {
        if (gettype($table) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        return $this->getPdo()->query("SELECT * FROM {$table}")->fetchAll();
    }

    /**
     * Returns the columns of a table.
     * @param  string $table The table inspected for its columns.
     * @return array         The columns of the table.        
     */
    abstract public function getColumns($table);

    /**
     * Returns the primary key of a table.
     * @param  string $table The table inspected for its primary key.
     * @return string        The primary key of the table.
     */
    abstract public function getPrimaryKey($table);

    /**
     * Update a record in the database.
     * @param  string $table  The table where the record update is being made.
     * @param  string $pk     The primary key value of the record to be updated.
     * @param  array  $record The updates to be made to the record in the database.
     * @return bool           Returns boolean true if the record was successfully updated or else it returns false.
     */
    public function updateRecord($table, $pk, $record)
    {        
        if (gettype($table) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        } 

        if (gettype($pk) !== 'string') {
            throw new Exception("Error Processing Request", 1);            
        }
        
        if (gettype($record) !== 'array') {
            throw new Exception("Error Processing Request", 1);            
        } 

        $count = count($record);

        $sql = "UPDATE {$table} SET ";
        foreach ($record as $key => $value) {
            if ($count > 1) {
                $sql = $sql."{$key}={$value}, ";
            } else {
                $sql = $sql."{$key}={$value} ";
            }
            $count--;
        }
        $sql .= "WHERE {$this->getPrimaryKey($table)}={$pk}";

        return $this->getPdo()->prepare($sql)->execute();
    }
}
