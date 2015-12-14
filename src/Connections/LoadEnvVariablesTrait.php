<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Exception;
use Dotenv\Dotenv;
use RuntimeException;

trait LoadEnvVariablesTrait
{
    /**
     * $_database The database name.
     *
     * @var string
     */
    private $_database;

    /**
     * $_host The host name.
     *
     * @var string
     */
    private $_host;

    /**
     * $_password The password to the database server.
     *
     * @var string
     */
    private $_password;

    /**
     *  $_port The port number to the database server.
     *
     * @var string
     */
    private $_port;

    /**
     * The username to the database server.
     *
     * @var string
     */
    private $_username;

    /**
     * Loads variables in the .env file.
     *
     * @return void
     */
    private function loadDbEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/../..');
        $dotenv->load();

        $dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT'])->notEmpty();

        $this->_host = getenv('DB_HOST');
        $this->_database = getenv('DB_DATABASE');
        $this->_username = getenv('DB_USERNAME');
        $this->_password = getenv('DB_PASSWORD');
        $this->_port = getenv('DB_PORT');
    }

    /**
     * Loads variables in the .env file and handles exceptions.
     *
     * @return void
     */
    private function useDbEnv()
    {
        try {
            $this->loadDbEnv();
        } catch (RuntimeException $e) {
        } catch (Exception $e) {
        }
    }
}