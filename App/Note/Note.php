<?php


namespace App\Note;
use App\Conf\Conf;
use Medoo\Medoo;


class Note
{
    private $database;

    /**
     * Note constructor.
     */
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
    }


    public function visit($id)
    {

    }

}