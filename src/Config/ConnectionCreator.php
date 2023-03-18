<?php

namespace HackbartPR\Config;

use \PDO;

final class ConnectionCreator
{
    static function create(): PDO
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}";
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

        $connection = new PDO($dsn, $username, $password, $options);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}