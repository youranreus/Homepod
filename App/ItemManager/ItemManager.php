<?php


namespace App\ItemManager;
use App\Conf\Conf;
use App\Core\cache;
use Medoo\Medoo;
use mysqli;
use PDOStatement;


class ItemManager
{

    private $database;

    /**
     * ItemManager constructor.
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

    /**
     * @param $action
     * User: youranreus
     * Date: 2021/3/4 15:43
     */
    public static function go($action)
    {
        $ItemManager = new ItemManager();
        $result = $ItemManager->$action();
        if(!is_bool($result))
        {
            echo json_encode($result);
        }
        exit();
    }

    /**
     * @return int|mixed|string|null
     * User: youranreus
     * Date: 2021/3/4 16:29
     */
    public function addItem()
    {
        /*
         * name 物品名称
         * buyer 购买者
         * buydate 购买日期
         * price 价格
         * from 购买方
         * cate 分类
         * tag 标签
         * desc 描述
         * link 链接
         */
        if( isset($_GET["name"]) and isset($_GET["buyer"]) and isset($_GET["price"]) and isset($_GET["buydate"]) and isset($_GET["from"]) and isset($_GET["cate"]) and isset($_GET["link"]) and isset($_GET["tag"]) and  isset($_GET["desc"]))
        {
            $this->database->insert("item", [
                "name" => $_GET["name"],
                "buyer" => $_GET["buyer"],
                "buydate" => $_GET["buydate"],
                "from" => $_GET["from"],
                "cate" => $_GET["cate"],
                "tag" => $_GET["tag"],
                "desc" => $_GET["desc"],
                "link" => $_GET["link"],
                "price" => $_GET["price"]
            ]);

            return $this->database->id();
        }
        
        return "参数缺失";
    }

    /**
     * @return array|false|string
     * User: youranreus
     * Date: 2021/3/14 21:34
     */
    private function getItem()
    {
        if(isset($_GET["id"]))
        {
            return $this->database->select("item", "*", [
                "id" => $_GET["id"]
            ]);
        }

        return "参数缺失";
    }

}