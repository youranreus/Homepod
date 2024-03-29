<?php
namespace App\Module\Wiki;
use App\Core\BaseController;
use App\Conf\Conf;
use App\Sec\Sec;
error_reporting(0);

class Wiki extends BaseController
{
    private $Sec;

    /**
     * wiki constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Sec = new Sec();
    }

    /**
     * @return array|false
     * User: youranreus
     * Date: 2021/8/1 11:17
     */
    public function initDB()
    {
        return $this->database->query("
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
    }

    /**
     * User: youranreus
     * Date: 2021/3/16 14:52
     * @param $page
     * @return array|false
     */
    public function getWikiList($page){
        $page = ($page-1) * Conf::$WikiPageLimit;
        if(isset($_GET['cate'])){
            return $this->database->select("wiki",["id","title","cate","date"], [
                "cate" => $_GET["cate"],
                "LIMIT" => [$page , Conf::$WikiPageLimit]
            ]);
        }
        return $this->database->select("wiki", "*",["LIMIT" => [$page , Conf::$WikiPageLimit]]);
    }

    /**
     * @param $id
     * User: youranreus
     * Date: 2020/12/22 18:55
     */
    public function getWikiDetail($id)
    {
        if(!isset($id)){
            return array("msg"=>Conf::$WikiIDMissing);
        }
        return $this->database->select("wiki","*", [
            "id" => $id
        ]);
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
            return array("msg"=>Conf::$msgOnParamMissing);
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
     * User: youranreus
     * Date: 2021/3/16 14:49
     * @param $id
     */
    public function deleteWiki($id): array
    {

        $this->Sec->accessCheck("get");

        if(!isset($id)){
            return array("msg"=>Conf::$msgOnParamMissing);
        }

        $action=$this->database->delete("wiki", [
            "id" => $id
        ]);

        return array("msg"=>"ok","rows"=>$action->rowCount());
    }

    /**
     * @param $keyword
     * User: youranreus
     * Date: 2021/3/19 12:14
     */
    public function search($keyword)
    {
        $keywords = explode(" ",urldecode($keyword));
        return $this->database->select(
            "wiki",
            "*",
            ["title[~]" => $keywords]
        );
    }

}