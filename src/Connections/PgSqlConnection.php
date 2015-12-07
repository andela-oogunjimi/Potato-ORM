<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

final class PgSqlConnection extends Connection
{
    /**
     * The method called in the constructor.
     * @return void
     */
    protected function connect()
    {
        $this->useDbEnv();

        $dsn = 'pgsql:host='.$this->_host;
        $dsn .= (isset($this->_port)) ? ';port='.$this->_port : '';
        $dsn .= ';dbname='.$this->_database;
        $dsn .= ';user='.$this->_username;
        $dsn .= ';password='.$this->_password;

        try {
            $this->_pdo = new PDO($dsn);
        } catch (PDOException $e) {
        }
    }

    /**
     * Returns the columns of a table.
     * @param  string $table The table inspected for its columns.
     * @return array         The columns of the table.        
     */
    public function getColumns($table)
    {
        return $this->getPdo()->query("SELECT COLUMN_NAME
                                        FROM {$this->_database}.INFORMATION_SCHEMA.COLUMNS
                                        WHERE TABLE_NAME = N{$table}")->fetchAll();
    }

    /**
     * Returns the primary key of a table.
     * @param  string $table The table inspected for its primary key.
     * @return string        The primary key of the table.
     */
    public function getPrimaryKey($table)
    {
        return $this->getPdo()->query("SELECT KU.table_name as tablename,column_name as primarykeycolumn
                                        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
                                        INNER JOIN
                                        INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU
                                        ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND
                                        TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME
                                        and ku.table_name='{$table}'
                                        ORDER BY KU.TABLE_NAME, KU.ORDINAL_POSITION;")->fetchAll()[0][$table];
    }
}






