<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

class ConnectionFactory
{
	private function __construct()
	{
	}

	public static function load()
	{
        switch () 
        {
        	case 'mysql':
		        return new MySqlConnection();
        		break;

			case 'pgsql':
        		return new PgSqlConnection();
        		break;

        	default:
        		break;
        }		
	}
}