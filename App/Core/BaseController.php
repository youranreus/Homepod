<?php


namespace App\Core;
use App\Conf\Conf;
use Medoo\Medoo;

class BaseController
{
    protected $database;
    protected $cache;

    public function __construct()
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => Conf::$dbname,
            'server' => Conf::$servername,
            'username' => Conf::$username,
            'password' => Conf::$password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
        ]);
        $this->cache = new cache();
    }
}