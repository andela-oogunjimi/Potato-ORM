<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

class MySqlConnection implements Connection
{

	use Opeyemiabiodun\PotatoORM\Connections\Environment;

	private $_connection;

    private function __construct()
    {
    	$this->useDbEnv();

    	$dsn = 'mysql:host=' . $this->_host 
    			. ';port=' . $this->_port 
    			. ';dbname=' . $this->_database;

    	try 
    	{
    		$this->_connection = new PDO($dsn, $this->_username, $this->_password);
    	}
    	catch (PDOException $e)
    	{

    	}
    }

    public static function load()
    {
        return new MySqlConnection();
    }
}