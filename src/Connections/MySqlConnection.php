<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

final class MySqlConnection extends Connection
{
    protected function connect()
    {
        $this->useDbEnv();

        $dsn = 'mysql:host='.$this->_host
                .';port='.$this->_port
                .';dbname='.$this->_database;

        try {
            $this->_pdo = new PDO($dsn, $this->_username, $this->_password);
        } catch (PDOException $e) {
        }
    }

    public function getColumns($table)
    {
        return $this->getPdo()->query("SELECT COLUMN_NAME
                                        FROM INFORMATION_SCHEMA.COLUMNS
                                        WHERE TABLE_NAME = N{$table} 
                                        AND TABLE_SCHEMA = N{$this->_database}")->fetchAll();
    }

    public function getPrimaryKey($table)
    {
        return $this->getPdo()->query("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'")->fetchAll()[0]["Column_name"];
    }
}
