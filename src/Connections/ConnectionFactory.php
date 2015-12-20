<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Dotenv\Dotenv;

class ConnectionFactory
{
    private static $_mySqlConnection;

    private static $_pgSqlConnection;

    private static $_sqliteConnection;

    private function __construct()
    {
    }

    public static function load()
    {
        $dotenv = new Dotenv(__DIR__.'/../..');
        $dotenv->load();

        $dotenv->required(['DB_ENGINE'])->allowedValues(['mysql', 'pgsql', 'sqlite']);

        switch (getenv('DB_ENGINE')) {
                    case 'mysql':
                                if (self::$_mySqlConnection == null) {
                                    self::$_mySqlConnection = new MySqlConnection();
                                }

                        return self::$_mySqlConnection;

                case 'pgsql':
                                if (self::$_pgSqlConnection == null) {
                                    self::$_pgSqlConnection = new PgSqlConnection();
                                }

                                return self::$_pgSqlConnection;

                        case 'sqlite':
                                if (self::$_sqliteConnection == null) {
                                    self::$_sqliteConnection = new SqliteConnection();
                                }

                                return self::$_sqliteConnection;
                }
    }
}
