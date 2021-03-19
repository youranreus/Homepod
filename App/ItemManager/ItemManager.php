<?php


namespace App\ItemManager;
use App\Conf\Conf;
use App\Sec\Sec;
use Medoo\Medoo;
use voku\helper\HtmlDomParser;


class ItemManager
{

    private $database;
    private $Sec;

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
        $this->Sec = new Sec();
        $this->Sec->accessCheck("get");
        ini_set('user_agent',Conf::$UserAgent);
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
     * User: youranreus
     * Date: 2021/3/16 14:45
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

        if($this->parameterCheck())
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

            exit(json_encode($this->database->id()));
        }
        
        exit(json_encode("参数缺失"));
    }

    /**
     * User: youranreus
     * Date: 2021/3/16 14:46
     * @param $id
     */
    public function getItem($id)
    {

        if(isset($id))
        {
            exit(json_encode($this->database->select("item", "*", [
                "id" => $id
            ])));
        }

        exit(json_encode("参数缺失"));
    }

    /**
     * User: youranreus
     * Date: 2021/3/17 19:33
     * @param $page
     */
    public function getItemList($page)
    {
        $page = ($page-1) * Conf::$ItemPageLimit;
        exit(json_encode($this->database->select(
            "item",
            ["id","name","price","cate","tag"],
            ["LIMIT" => [$page , Conf::$ItemPageLimit]]
        )));
    }

    /**
     * @param $keyword
     * User: youranreus
     * Date: 2021/3/17 22:53
     */
    public function search($keyword)
    {
        $keywords = explode(" ",urldecode($keyword));
        exit(json_encode($this->database->select(
            "item",
            ["id","name","price","cate","tag"],
            ["name[~]" => $keywords]
        )));
    }


    /**
     * @param $id
     * User: youranreus
     * Date: 2021/3/17 19:30
     */
    public function updateItem($id)
    {

        if($this->parameterCheck())
        {
            $this->database->update("item", [
                "name" => $_GET["name"],
                "buyer" => $_GET["buyer"],
                "buydate" => $_GET["buydate"],
                "from" => $_GET["from"],
                "cate" => $_GET["cate"],
                "tag" => $_GET["tag"],
                "desc" => $_GET["desc"],
                "link" => $_GET["link"],
                "price" => $_GET["price"]
            ],[
                "id" => $id
            ]);

            exit(json_encode($this->database->id()));;
        }

        exit(json_encode("参数缺失"));

    }

    /**
     * User: youranreus
     * Date: 2021/3/19 22:03
     */
    public function getItemByDate()
    {
        if(isset($_GET["type"]) and isset($_GET["date"]))
        {
            if($_GET["type"] == 'before')
            {
                exit(json_encode($this->database->select(
                    "item",
                    ["id","name","price","cate","tag"],
                    [
                        "buydate[><]"=>[$_GET["date"],date('Y-m-d')]
                    ])));
            }

            if($_GET["type"] == 'after')
            {
                exit(json_encode($this->database->select(
                    "item",
                    ["id","name","price","cate","tag"],
                    [
                        "buydate[<>]"=>[$_GET["date"],date('Y-m-d')]
                    ])));
            }

        }
        exit(json_encode("参数缺失"));
    }


    /**
     * @return bool
     * User: youranreus
     * Date: 2021/3/16 21:23
     */
    private function parameterCheck(): bool
    {
        if(isset($_GET["name"]) and isset($_GET["buyer"]) and isset($_GET["price"]) and isset($_GET["buydate"]) and isset($_GET["from"]) and isset($_GET["cate"]) and isset($_GET["link"]) and isset($_GET["tag"]) and  isset($_GET["desc"]))
        {
            return true;
        }

        return false;
    }

    /**
     * User: youranreus
     * Date: 2021/3/19 22:03
     */
    public function getJDItemByLink()
    {
        if(!isset($_GET['link']))
        {
            exit(json_encode("参数缺失"));
        }

        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];

        $itemMeta = [
            "price"=> 0,
            "name"=> "",
            "skuid"=>""
        ];

        $id = parse_url($_GET['link'])['path'];
        $id = preg_replace("/\//i","",$id);
        $id = preg_replace("/.html/i","",$id);
        $itemMeta["skuid"] = $id;

        $buff = file_get_contents($_GET["link"],false, stream_context_create($stream_opts)) or die("无法打开该网站");
        $dom = HtmlDomParser::str_get_html($buff);
        $itemMeta["name"] = $dom->findOne("title")->nodeValue;
        $itemMeta["name"] = preg_replace("/【图片 价格 品牌 报价】-京东/i","",$itemMeta["name"]);
        $itemMeta["name"] = preg_replace("/【行情 报价 价格 评测】-京东/i","",$itemMeta["name"]);
        $buff = file_get_contents("http://p.3.cn/prices/mgets?skuIds=J_".$id,false, stream_context_create($stream_opts)) or die("无法获取价格");
        $itemMeta["price"] = json_decode($buff)[0]->p;

        exit(json_encode($itemMeta));
    }


}