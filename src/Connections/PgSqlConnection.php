<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use RuntimeException;
use InvalidArgumentException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;
use Opeyemiabiodun\PotatoORM\Connections\LoadEnvVariablesTrait;
use Opeyemiabiodun\PotatoORM\Connections\DatabaseTransactionsTrait;

final class PgSqlConnection implements Connection
{
    use LoadEnvVariablesTrait, DatabaseTransactionsTrait;

    /**
     * $_pdo The PDO instance of the connection.
     *
     * @var PDO
     */
    private $_pdo;
    
    /**
     * The method called in the constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->useDbEnv();

        $dsn = "pgsql:host={$this->_host};port={$this->_port};dbname={$this->_database};user={$this->_username};password={$this->_password}";

        try {
            $this->_pdo = new PDO($dsn);
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
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not an string. A string is required instead.");
        }

        return $this->getPdo()->query("SELECT COLUMN_NAME
                                            FROM {$this->_database}.INFORMATION_SCHEMA.COLUMNS
                                            WHERE TABLE_NAME = N'{$table}'")->fetchAll();
    }

    /**
     * Returns the Connection's PDO.
     *
     * @return PDO PHP Data Objects
     */
    public function getPdo()
    {
        return $this->_pdo;
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
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not an string. A string is required instead.");
        }

        $array = $this->getPdo()->query("SELECT KU.table_name as tablename,column_name as primarykeycolumn
                                            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
                                            INNER JOIN
                                            INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU
                                            ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND
                                            TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME
                                            and ku.table_name='{$table}'
                                            ORDER BY KU.TABLE_NAME, KU.ORDINAL_POSITION;")->fetchAll();
        if (count($array) === 0) {
            throw new RuntimeException("Error Processing Request", 1);
        }

        return $array[0]['primarykeycolumn'];
    }
}
