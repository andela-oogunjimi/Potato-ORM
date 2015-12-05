<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Dotenv\Dotenv;
use Exception;
use RuntimeException;

abstract class Connection
{
    protected $_database;

    protected $_host;

    protected $_password;

    protected $_pdo;

    protected $_port;

    protected $_username;

    public function __construct()
    {
        $this->connect();
    }

    abstract protected function connect();

    public function getPdo()
    {
        return $this->_pdo;
    }

    protected function loadDbEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])->notEmpty();
        $dotenv->required(['DB_PORT']);
        $dotenv->load();

        $this->_host = getenv('DB_HOST');
        $this->_database = getenv('DB_DATABASE');
        $this->_username = getenv('DB_USERNAME');
        $this->_password = getenv('DB_PASSWORD');
        $this->_port = getenv('DB_PORT');
    }

    protected function useDbEnv()
    {
        try {
            $this->loadDbEnv();
        } catch (RuntimeException $e) {
        } catch (Exception $e) {
        }
    }

    public function createRecord($table, $record)
    {
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

        $this->getPdo()->prepare($sql)->execute();
    }

    public function deleteRecord($table, $pk)
    {
        return $this->getPdo()->prepare("DELETE FROM {$table}
                                            WHERE {$this->getPrimaryKey($table)}={$pk}")->execute();
    }

    public function findRecord($table, $pk)
    {
        return $this->getPdo()->query("SELECT * FROM {$table}
                                        WHERE {$this->getPrimaryKey($table)}={$pk}")->fetchAll();
    }

    public function getAllRecords($table)
    {
        return $this->getPdo()->query("SELECT * FROM {$table}")->fetchAll();
    }

    abstract public function getColumns($table);

    abstract public function getPrimaryKey($table);

    public function updateRecord($table, $pk, $record)
    {
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
