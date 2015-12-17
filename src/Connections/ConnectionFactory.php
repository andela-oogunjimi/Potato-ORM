<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Dotenv\Dotenv;
use Opeyemiabiodun\PotatoORM\Connections\PgSqlConnection;
use Opeyemiabiodun\PotatoORM\Connections\MySqlConnection;
use Opeyemiabiodun\PotatoORM\Connections\SqliteConnection;

class ConnectionFactory
{
	private function __construct()
	{
	}

	public static function load()
	{

                $dotenv = new Dotenv(__DIR__.'/../..');
                $dotenv->load();

                $dotenv->required(['DB_ENGINE'])->allowedValues(['mysql', 'pgsql', 'sqlite']);

                switch (getenv('DB_ENGINE')) 
                {
                	case 'mysql':
        		        return new MySqlConnection();
                		break;

        		case 'pgsql':
                		return new PgSqlConnection();
                		break;

                        case 'sqlite':
                                return new SqliteConnection();
                                break;
                }		
	}
}