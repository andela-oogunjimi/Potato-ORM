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
        $this->create();
    }

    abstract protected function create();

    public function getPdo()
    {
        return $this->_pdo;
    }

    protected function loadDbEnv()
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT'])->notEmpty();
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
}
