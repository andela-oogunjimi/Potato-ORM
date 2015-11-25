<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Exception;
use Dotenv\Dotenv;
use RuntimeException;

trait Environment 
{

	private $_database; 

	private $_host;

	private $_password;

    private $_port;

	private $_username;

	private function loadDbEnv()
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

    private function useDbEnv()
    {
    	try
    	{
    		$this->loadDbEnv();
    	} 
    	catch (RuntimeException $e) 
    	{
    		
    	}
    	catch (Exception $e)
    	{

    	}
    }
}