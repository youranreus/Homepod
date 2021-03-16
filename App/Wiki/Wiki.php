<?php
namespace App\Wiki;
use App\Core\X;
use Medoo\Medoo;
use App\Conf\Conf;
error_reporting(0);

class Wiki
{
    private $database;

    /**
     * wiki constructor.
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
     * Date: 2020/12/23 10:26
     */
    public static function go($action)
    {
        $Wiki = new Wiki();
        $result = $Wiki->$action();
        if(!is_bool($result))
        {
            echo json_encode($result);
        }
        exit();
    }


    /**
     * User: youranreus
     * Date: 2021/3/16 14:52
     */
    public function getWikiList(){
        if(isset($_GET['cate'])){
            $Wikis = $this->database->select("wiki",["id","title","cate","date"], [
                "cate" => $_GET["cate"]
            ]);
            exit(json_encode($Wikis));
        }
        $Wikis = $this->database->select("wiki", "*");
        exit(json_encode($Wikis));
    }

    /**
     * @return string[]
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function getWikiDetail(){
        if(!isset($_GET['id'])){
            exit(json_encode(array("msg"=>"ID缺失")));
        }
        $Wiki = $this->database->select("wiki","*", [
            "id" => $_GET["id"]
        ]);
        exit(json_encode($Wiki));
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function postWiki(){

        $this->accessCheck("post");

        if(!isset($_POST["author"]) or !isset($_POST["title"]) or !isset($_POST["cate"]) or !isset($_POST["likes"]) or !isset($_POST["contents"])){
            exit(json_encode(array("msg"=>"数据缺失")));
        }

        $this->database->insert("wiki", [
            "title" => $_POST["title"],
            "date" => date("yy-m-d"),
            "cate" => $_POST["cate"],
            "author"=>$_POST["author"],
            "likes"=>$_POST["likes"],
            "contents"=>$_POST["contents"],
        ]);

        exit(json_encode(array("msg"=>"ok","id"=>$this->database->id())));
    }

    /**
     * User: youranreus
     * Date: 2021/3/16 14:49
     */
    public function deleteWiki(){

        $this->accessCheck("get");

        if(!isset($_GET["id"])){
            exit(json_encode(array("msg"=>"数据缺失")));
        }

        $action=$this->database->delete("wiki", [
            "id" => $_GET["id"]
        ]);

        exit(json_encode(array("msg"=>"ok","rows"=>$action->rowCount())));
    }

    /**
     * User: youranreus
     * Date: 2020/12/21 23:42
     */
    public function test(){
        echo date("yy-m-d");
    }

    /**
     * @param $method
     * User: youranreus
     * Date: 2021/3/16 14:48
     */
    private function accessCheck($method){

        if($method == 'get'){
            if(!isset($_GET["key"])){
                exit(json_encode(array("msg"=>"数据缺失")));
            }
            if($_GET["key"]!=Conf::$key){
                exit(json_encode(array("msg"=>"密钥错误错误")));
            }
        }
        if($method == 'post'){
            if(!isset($_POST["key"])){
                exit(json_encode(array("msg"=>"数据缺失")));
            }
            if($_POST["key"]!=Conf::$key){
                exit(json_encode(array("msg"=>"密钥错误错误")));
            }
        }


    }

}