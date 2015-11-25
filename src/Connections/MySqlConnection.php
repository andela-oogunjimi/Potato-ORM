<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;

final class MySqlConnection extends Connection
{
    protected function create()
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
}
