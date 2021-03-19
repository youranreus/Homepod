<?php
namespace App\Wiki;
use Medoo\Medoo;
use App\Conf\Conf;
use App\Sec\Sec;
error_reporting(0);

class Wiki
{
    private $database;
    private $Sec;

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

        $this->Sec = new Sec();
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
     * @param $page
     */
    public function getWikiList($page){
        $page = ($page-1) * Conf::$WikiPageLimit;
        if(isset($_GET['cate'])){
            $Wikis = $this->database->select("wiki",["id","title","cate","date"], [
                "cate" => $_GET["cate"],
                "LIMIT" => [$page , Conf::$WikiPageLimit]
            ]);
            exit(json_encode($Wikis));
        }
        $Wikis = $this->database->select("wiki", "*",["LIMIT" => [$page , Conf::$WikiPageLimit]]);
        exit(json_encode($Wikis));
    }

    /**
     * @param $id
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function getWikiDetail($id)
    {
        if(!isset($id)){
            exit(json_encode(array("msg"=>"ID缺失")));
        }
        $Wiki = $this->database->select("wiki","*", [
            "id" => $id
        ]);
        exit(json_encode($Wiki));
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function postWiki(): array
    {

        $this->Sec->accessCheck("post");

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
     * @param $id
     */
    public function deleteWiki($id){

        $this->Sec->accessCheck("get");

        if(!isset($id)){
            exit(json_encode(array("msg"=>"数据缺失")));
        }

        $action=$this->database->delete("wiki", [
            "id" => $id
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
     * @param $keyword
     * User: youranreus
     * Date: 2021/3/19 12:14
     */
    public function search($keyword)
    {
        $keywords = explode(" ",urldecode($keyword));
        exit(json_encode($this->database->select(
            "wiki",
            "*",
            ["title[~]" => $keywords]
        )));
    }

}