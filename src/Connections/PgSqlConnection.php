<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

final class PgSqlConnection extends Connection
{
    protected function create()
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
}
