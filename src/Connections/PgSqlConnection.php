<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

final class PgSqlConnection extends Connection
{
    protected function connect()
    {
        $this->useDbEnv();

        $dsn = 'pgsql:host='.$this->_host
                .';port='.$this->_port
                .';dbname='.$this->_database
                .';user='.$this->_username
                .';password='.$this->_password;

        try {
            $this->_pdo = new PDO($dsn);
        } catch (PDOException $e) {
        }
    }

    public function getColumns($table)
    {
        return $this->getPdo()->query("SELECT COLUMN_NAME
                                        FROM {$this->_database}.INFORMATION_SCHEMA.COLUMNS
                                        WHERE TABLE_NAME = N{$table}")->fetchAll();
    }

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






