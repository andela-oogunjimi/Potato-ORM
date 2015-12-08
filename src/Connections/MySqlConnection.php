<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use InvalidArgumentException;

final class MySqlConnection extends Connection
{
    /**
     * The method called in the constructor.
     *
     * @return void
     */
    protected function connect()
    {
        $this->useDbEnv();

        $dsn = "mysql:host=".$this->_host;
        $dsn .= (isset($this->_port)) ? ";port=".$this->_port : "";
        $dsn .= ";dbname=".$this->_database;

        try {
            $this->_pdo = new PDO($dsn, $this->_username, $this->_password);
        } catch (PDOException $e) {
        }
    }

    /**
     * Returns the columns of a table.
     *
     * @param string $table The table inspected for its columns.
     *
     * @return array The columns of the table.        
     */
    public function getColumns($table)
    {
        if (gettype($table) !== "string") {
            throw new InvalidArgumentException("The parameter {$table} is not an string. A string is required instead.");
        }

        return $this->getPdo()->query("SELECT COLUMN_NAME
                                        FROM INFORMATION_SCHEMA.COLUMNS
                                        WHERE TABLE_NAME = N'{$table}' 
                                        AND TABLE_SCHEMA = N'{$this->_database}'")->fetchAll();
    }

    /**
     * Returns the primary key of a table.
     *
     * @param string $table The table inspected for its primary key.
     *
     * @return string The primary key of the table.
     */
    public function getPrimaryKey($table)
    {
        if (gettype($table) !== "string") {
            throw new InvalidArgumentException("The parameter {$table} is not an string. A string is required instead.");
        }

        return $this->getPdo()->query("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'")->fetchAll()[0]["Column_name"];
    }
}
