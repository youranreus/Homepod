<?php
namespace App\Wiki;
use App\X\X;
use Medoo\Medoo;
use App\Conf\Conf;

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
    public function go($action)
    {
        echo json_encode($this->$action());
    }


    /**
     * @return mixed
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function getWikiList(){
        if(isset($_GET['cate'])){
            $Wikis = $this->database->select("wiki",["id","title","cate","date"], [
                "cate" => $_GET["cate"]
            ]);
            return $Wikis;
        }
        $Wikis = $this->database->select("wiki", "*");
        return $Wikis;
    }

    /**
     * @return string[]
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function getWikiDetail(){
        if(!isset($_GET['id'])){
            return array("msg"=>"ID确实");
        }
        $Wiki = $this->database->select("wiki","*", [
            "id" => $_GET["id"]
        ]);
        return $Wiki;
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

        return array("msg"=>"ok","id"=>$this->database->id());
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function deleteWiki(){

        $this->accessCheck("get");

        if(!isset($_GET["id"])){
            exit(json_encode(array("msg"=>"数据缺失")));
        }

        $action=$this->database->delete("wiki", [
            "id" => $_GET["id"]
        ]);

        return array("msg"=>"ok","rows"=>$action->rowCount());
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
     * @return string[]
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    private function accessCheck($method){

        if($method == 'get'){
            if(!isset($_GET["key"])){
                return array("msg"=>"数据缺失");
            }
            if($_GET["key"]!=Conf::$key){
                return array("msg"=>"密钥错误错误");
            }
        }
        if($method == 'post'){
            if(!isset($_POST["key"])){
                return array("msg"=>"数据缺失");
            }
            if($_POST["key"]!=Conf::$key){
                return array("msg"=>"密钥错误错误");
            }
        }


    }

}