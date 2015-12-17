<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use RuntimeException;
use InvalidArgumentException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;
use Opeyemiabiodun\PotatoORM\Connections\DatabaseTransactionsTrait;

final class SqliteConnection implements Connection
{
    use DatabaseTransactionsTrait;

    /**
     * $_pdo The PDO instance of the connection.
     *
     * @var PDO
     */
    private $_pdo;

    /**
     * $columns Columns of the database table.
     *
     * @var array
     */
    private $columns = [];

    /**
     * $keys Keys of the database table.
     *
     * @var array
     */
    private $keys = [];

    /**
     * The method called in the constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $dsn = "sqlite:../../potatodb.sq3";

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

        $this->loadColumnInfo($table);

        return $this->columns[$table];
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

        $this->loadColumnInfo($table);

        return $this->keys[$table][0];
    }

    private function loadColumnInfo($table)
    {
        $query     = "PRAGMA table_info('{$table}')";
        $statement = $this->_pdo->query($query);

        /* @var $statement PDOStatement */
        $this->columns[$table] = [];
        $this->keys[$table]    = [];

        while ($columnData = $statement->fetch(PDO::FETCH_NUM)) {

            $this->columns[$table][] = ["column_name" => $columnData[1]];

            if ($columnData[5] == 1) {
                $this->keys[$table][] = $columnData[1];
            }

        }
    }
}
