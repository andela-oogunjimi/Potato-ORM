<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use Dotenv\Dotenv;

class ConnectionFactory
{
    private static $_sqliteConnection;

    private function __construct()
    {
    }

    public static function load()
    {
        $dotenv = new Dotenv(__DIR__.'/../..');
        $dotenv->load();

        $dotenv->required(['DB_ENGINE'])->allowedValues(['sqlite']);

        switch (getenv('DB_ENGINE')) {

            case 'sqlite':
                if (self::$_sqliteConnection == null) {
                    self::$_sqliteConnection = new SqliteConnection();
                }

                return self::$_sqliteConnection;
        }
    }
}
