<?php


namespace App\Core;
use App\Conf\Conf;
use Medoo\Medoo;

class DB
{
    private $database;
    private $tableList;

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
        $this->tableList = (new X())->getModuleList();
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2021/3/18 16:30
     */
    public function tableCheck(): array
    {
        $existNicht = array();
        foreach ($this->tableList as $table)
        {
            $result = $this->database->query("SHOW TABLES LIKE :table",[":table"=>$table])->fetchAll();
            if(count($result) != '1')
            {
                $existNicht[] = $table;
            }
        }

        return $existNicht;
    }


}