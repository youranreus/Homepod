<?php
namespace App\Core;
use App\Conf\Conf;
use App\Sec\Sec;
use mysqli;
use Symfony\Component\Yaml\Yaml;

error_reporting(0);


class X
{
    private $cache;
    private $Sec;

    public function __construct()
    {
        $this->cache = new cache();
        $this->Sec = new Sec();
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2021/7/25 19:49
     */
    public function status(): array
    {
        $status = array(
            "msg"=>Conf::$msgOnStatusError,
            "DB"=>Conf::$msgOnDBDown,
            "version"=>Conf::$Version,
            "websiteStatus"=>$this->WebsiteCheck()
        );
        if($this->DBCheck()){
            $status["DB"] = Conf::$msgOnDBOk;
            $status["msg"] = Conf::$msgOnStatusFine;
        }

        return $status;
    }

    /**
     * @return bool
     * User: youranreus
     * Date: 2020/12/21 23:41
     */
    public function DBCheck(): bool
    {
        $conn = new mysqli(Conf::$servername, Conf::$username, Conf::$password);
        // 检测连接
        if ($conn->connect_error) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2021/3/16 14:33
     */
    private function WebsiteCheck(): array
    {

        $websiteStatus = array();
        $n = count(Conf::$websites);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl,CURLOPT_NOBODY,true);
        curl_setopt($curl, CURLOPT_USERAGENT, Conf::$UserAgent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        for ($i=0;$i<$n;$i++){
            curl_setopt($curl, CURLOPT_URL, Conf::$websites[$i][1]);
            curl_exec($curl);
            $HttpCode=curl_getinfo($curl,CURLINFO_HTTP_CODE);
            $websiteStatus[$i][]=Conf::$websites[$i][0];
            $websiteStatus[$i][]=$HttpCode;
        }
        curl_close($curl);

        return $websiteStatus;
    }


    /**
     * User: youranreus
     * Date: 2021/3/15 23:19
     */
    public function getSites(): array
    {
        if(isset($_GET["type"]) and $_GET["type"] == "all")
        {
            return Conf::$websites;
        }
        $websites = array();
        for($i = 0;$i < count(Conf::$websites);$i++)
        {
            if(Conf::$websites[$i][2])
            {
                $websites[] = Conf::$websites[$i];
            }
        }
        return $websites;
    }

    /**
     * User: youranreus
     * Date: 2021/3/16 14:19
     */
    public function getBlogRSS()
    {
        $result = array();
        if(!isset($_GET["url"]))
        {
            return "请输入url";
        }
        if((isset($_GET["type"]) && $_GET["type"] == "force") || !$this->cache->haveCache(md5($_GET["url"])))
        {
            $stream_opts = [
                "ssl" => [
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ]
            ];

            $buff= file_get_contents($_GET["url"],false, stream_context_create($stream_opts)) or die("无法打开该网站Feed");

            $parser = xml_parser_create();
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
            xml_parse_into_struct($parser, $buff, $values, $idx);
            xml_parser_free($parser);


            foreach ($values as $val) {

                $tag = $val["tag"];
                $type = $val["type"];
                $value = $val["value"];
                $tag = strtolower($tag);


                if ($tag == "item" && $type == "open") {
                    $is_item = 1;
                } else if ($tag == "item" && $type == "close") {
                    $is_item = 0;
                }
                //仅读取item标签中的内容
                if ($is_item == 1) {
                    if ($tag == "title") {
                        $result[]["title"] = $value;
                    }
                    if ($tag == "link") {
                        $result[]["link"] = $value;
                    }
                }
            }
            $resultNum = count($result);
            for($i = 0;$i<$resultNum;$i++){
                if($i % 2 == 0){
                    $result[$i]["title"] = html_entity_decode($result[$i]["title"], ENT_QUOTES);
                    $result[$i] = array_merge($result[$i],$result[$i+1]);
                }else{
                    unset($result[$i]);
                }
            }
            //写入缓存
            $this->cache->newCache(md5($_GET["url"]));
            $this->cache->writeCache(md5($_GET["url"]), $result);
            //输出结果
            return $result;
        }

        return $this->cache->readCache(md5($_GET["url"]));
    }

    /**
     * User: youranreus
     * Date: 2021/3/18 18:42
     */
    public function fixDBTable()
    {
        $this->Sec->accessCheck('get');
        $DB = new DB();
        if(count($DB->tableCheck()) == 0)
        {
            return "Table is ok";
        }
        return $DB->makeAllTables();
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2021/7/31 15:00
     */
    public function getModuleList(): array
    {
        $data = scandir(dirname(__FILE__).'\..');
        $result = [];
        for($i = 2;$i<count($data);$i++)
            if($data[$i] != 'Conf' && $data[$i] != 'Core' && $data[$i] != 'Sec' && $data[$i] != 'R.php')
                $result[] = $data[$i];
        return $result;
    }

    /**
     * @param string $Module
     * @return mixed
     * User: youranreus
     * Date: 2021/7/31 15:28
     */
    private function getModuleInfo(string $Module = 'ItemManager')
    {
        $dir = dirname(__FILE__).'\..\/'.$Module.'\/module.yml';
        return (new Yaml())->parseFile($dir);
    }

    /**
     * User: youranreus
     * Date: 2021/7/31 20:32
     */
    public function activeRouterRule(): void
    {
        $modules = $this->getModuleList();
        foreach($modules as $module)
        {
            $data = $this->getModuleInfo($module);
            if(isset($data['route']))
                foreach($data['route'] as $i)
                    foreach($i as $method => $content)
                        foreach ($content as $key => $value)
                            Router::{$method}($key,$value);
        }
    }

}