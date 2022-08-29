<?php

namespace app\database;

require('../vendor/autoload.php');

use Dotenv\Dotenv;
use PDO;

$dotenv = Dotenv::createImmutable(dirname(__FILE__,3));
$dotenv->load();

class Connection
{
    public static function connect()
    {
        return new PDO($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }
}