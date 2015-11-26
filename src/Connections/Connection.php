<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use PDO;

interface Connection
{
    public function beginTransaction();

    public function commit();

    public function errorCode();

    public function errorInfo();

    public function exec($statement);

    public function getAttribute($attribute);

    public function getAvailableDrivers();

    public function inTransaction();

    public function lastInsertId($name = null);

    public function prepare($statement, $driver_options = []);

    public function query($statement);

    public function quote($string, $parameter_type = PDO::PARAM_STR);

    public function rollBack();

    public function setAttribute($attribute, $value);
}

/*
    PDO {
        public __construct ( string $dsn [, string $username [, string $password [, array $options ]]] )
        public bool beginTransaction ( void )
        public bool commit ( void )
        public mixed errorCode ( void )
        public array errorInfo ( void )
        public int exec ( string $statement )
        public mixed getAttribute ( int $attribute )
    ####public static array getAvailableDrivers ( void )
        public bool inTransaction ( void )
        public string lastInsertId ([ string $name = NULL ] )
        public PDOStatement prepare ( string $statement [, array $driver_options = array() ] )
        public PDOStatement query ( string $statement )
        public string quote ( string $string [, int $parameter_type = PDO::PARAM_STR ] )
        public bool rollBack ( void )
        public bool setAttribute ( int $attribute , mixed $value )
    }
*/
