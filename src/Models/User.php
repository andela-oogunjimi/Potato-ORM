<?php

namespace Opeyemiabiodun\PotatoORM\Models;

use PDO;

class User
{
    use Model;

    public static function createTable(PDO $pdo)
    {
        $pdo->query('CREATE TABLE user_table
        				(
        					id INTEGER PRIMARY KEY,
        					name varchar(255),
        					address varchar(255),
        					phone varchar(255)
        				);');
    }
}
