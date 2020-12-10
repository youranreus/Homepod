<?php
include "Conf.class.php";
require_once 'Medoo.php';
use Medoo\Medoo;
$conf = new Conf();
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => $conf::$dbname,
    'server' => $conf::$servername,
    'username' => $conf::$username,
    'password' => $conf::$password,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
]);

class wiki
{
    private $conf;
    private $database;

    public function __construct()
    {
        $this->conf = $GLOBALS["conf"];
        $this->database = $GLOBALS["database"];
    }

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

    public function getWikiDetail(){
        if(!isset($_GET['id'])){
            exit(json_encode(array("msg"=>"ID确实")));
        }
        $Wiki = $this->database->select("wiki","*", [
            "id" => $_GET["id"]
        ]);
        exit(json_encode($Wiki));
    }

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

    public function test(){
        echo date("yy-m-d");
    }

    private function accessCheck($method){

        if($method == 'get'){
            if(!isset($_GET["key"])){
                exit(json_encode(array("msg"=>"数据缺失")));
            }
            if($_GET["key"]!=$this->conf::$key){
                exit(json_encode(array("msg"=>"密钥错误错误")));
            }
        }
        if($method == 'post'){
            if(!isset($_POST["key"])){
                exit(json_encode(array("msg"=>"数据缺失")));
            }
            if($_POST["key"]!=$this->conf::$key){
                exit(json_encode(array("msg"=>"密钥错误错误")));
            }
        }


    }

}