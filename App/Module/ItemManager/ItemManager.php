<?php


namespace App\Module\ItemManager;
use App\Conf\Conf;
use App\Core\BaseController;
use App\Sec\Sec;
use voku\helper\HtmlDomParser;


class ItemManager extends BaseController
{
    private $Sec;

    /**
     * ItemManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->Sec = new Sec();
        $this->Sec->accessCheck("get");
        ini_set('user_agent',Conf::$UserAgent);
    }

    /**
     * @return array|false
     * User: youranreus
     * Date: 2021/8/1 11:16
     */
    public function initDB()
    {
        return $this->database->query("
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

            return $this->database->id();
        }
        
        return ["msg"=>Conf::$msgOnParamMissing];
    }

    /**
     * User: youranreus
     * Date: 2021/3/16 14:46
     * @param $id
     * @return array|false
     */
    public function getItem($id)
    {

        if(isset($id))
        {
            return $this->database->select("item", "*", [
                "id" => $id
            ]);
        }

        return ["msg"=>Conf::$msgOnParamMissing];
    }

    /**
     * User: youranreus
     * Date: 2021/3/17 19:33
     * @param $page
     * @return array|false
     */
    public function getItemList($page)
    {
        $page = ($page-1) * Conf::$ItemPageLimit;
        return $this->database->select(
            "item",
            ["id","name","price","cate","tag"],
            ["LIMIT" => [$page , Conf::$ItemPageLimit]]
        );
    }

    /**
     * @param $keyword
     * @return array|false
     * User: youranreus
     * Date: 2021/7/25 19:24
     */
    public function search($keyword)
    {
        $keywords = explode(" ",urldecode($keyword));
        return $this->database->select(
            "item",
            ["id","name","price","cate","tag"],
            ["name[~]" => $keywords]
        );
    }


    /**
     * @param $id
     * @return array|int|mixed|string|null
     * User: youranreus
     * Date: 2021/7/25 19:24
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

            return $this->database->id();
        }

        return ["msg"=>Conf::$msgOnParamMissing];

    }

    /**
     * @return array|false
     * User: youranreus
     * Date: 2021/7/25 19:25
     */
    public function getItemByDate()
    {
        if(isset($_GET["type"]) and isset($_GET["date"]))
        {
            if($_GET["type"] == 'before')
            {
                return $this->database->select(
                    "item",
                    ["id","name","price","cate","tag"],
                    [
                        "buydate[><]"=>[$_GET["date"],date('Y-m-d')]
                    ]);
            }

            if($_GET["type"] == 'after')
            {
                return $this->database->select(
                    "item",
                    ["id","name","price","cate","tag"],
                    [
                        "buydate[<>]"=>[$_GET["date"],date('Y-m-d')]
                    ]);
            }

        }
        return ["msg"=>Conf::$msgOnParamMissing];
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
     * @return array
     * User: youranreus
     * Date: 2021/7/25 19:26
     */
    public function getJDItemByLink(): array
    {
        if(!isset($_GET['link']))
        {
            exit(json_encode(["msg"=>Conf::$msgOnParamMissing]));
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

        return $itemMeta;
    }


    /**
     * @param $id
     * @return array
     * User: youranreus
     * Date: 2021/7/25 19:26
     */
    public function throwItem($id): array
    {
        if(isset($_GET['date']))
        {
            $this->database->update("item", [
                "throwdate" => $_GET["date"],
            ],[
                "id" => $id
            ]);
        }
        else
        {
            $this->database->update("item", [
                "throwdate" => date('Y-m-d'),
            ],[
                "id" => $id
            ]);
        }

        return ["msg"=>Conf::$msgOnComplete];
    }


}