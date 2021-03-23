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
        $this->tableList = Conf::$tableList;
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

    /**
     * @return array
     * User: youranreus
     * Date: 2021/3/18 16:49
     */
    public function makeAllTables(): array
    {
        $result = array();
        $result[] = $this->database->query("
                    CREATE TABLE IF NOT EXISTS `item` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      `price` float NOT NULL DEFAULT '0',
                      `buydate` date NOT NULL,
                      `from` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      `throwdate` date DEFAULT NULL,
                      `buyer` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      `cate` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      `tag` text COLLATE utf8mb4_unicode_ci,
                      `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      `link` text COLLATE utf8mb4_unicode_ci NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ")->fetchAll();

        $result[] = $this->database->query("
            CREATE TABLE IF NOT EXISTS `wiki` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `author` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `contents` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `date` date NOT NULL,
                  `cate` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `mt` date NOT NULL,
                  `likes` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ")->fetchAll();

        $result[] = $this->database->query("
            CREATE TABLE IF NOT EXISTS `note` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `sid` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ")->fetchAll();

        return $result;
    }

}