<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;
use PDOException;
use Opeyemiabiodun\PotatoORM\Connections\Connection;

class PgSqlConnection implements Connection
{

	use Opeyemiabiodun\PotatoORM\Connections\Environment;

	private $_connection;

    private function __construct()
    {
    	$this->useDbEnv();

	    $dsn = 'pgsql:host=' . $this->_host 
	    		. ';port=' . $this->_port 
	    		. ';dbname=' . $this->_database 
	    		. ';user=' . $this->_username 
	    		. ';password=' . $this->_password;

	    try
    	{
	    	$this->_connection = new PDO($dsn);
    	}
    	catch (PDOException $e)
    	{

    	}    	
    }

    public static function load()
    {
        return new PgSqlConnection();
    }
}