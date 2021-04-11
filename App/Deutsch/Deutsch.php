<?php


namespace App\Deutsch;
use App\Core\BaseController;
use voku\helper\HtmlDomParser;

class Deutsch extends BaseController
{

    /**
     * Deutsch constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $url
     * @return false|string
     * User: youranreus
     * Date: 2021/4/8 22:21
     */
    private function getSiteContent($url)
    {
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];

        $buff = file_get_contents($url,false, stream_context_create($stream_opts)) or die("抓取失败");
        return $buff;
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2021/4/8 14:04
     * description: 来源：德语助手
     */
    private function getSentence(): array
    {
        $buff = $this->getSiteContent("http://www.godic.net/home/dailysentence/");
        $dom = HtmlDomParser::str_get_html($buff);
        $sentence = $dom->findOne('.sect_de')->innerhtml;
        $trans = $dom->findOne('.sect-trans')->innerhtml;

        $result = ["sentence"=>$sentence,"translation"=>$trans,"date"=>date('Y-m-d')];
        //写入缓存
        $this->cache->newCache("DSentence");
        $this->cache->writeCache("DSentence", $result);
        //写入数据库
        $this->database->insert("dailysentence",["date"=>date('Y-m-d'),"data"=>$result]);

        return $result;
    }

    /**
     * User: youranreus
     * Date: 2021/4/8 22:19
     */
    public function dailySentence()
    {
        if($this->cache->haveCache("DSentence"))
        {
            $result = $this->cache->readCache("DSentence");
            if(strtotime($result->date) == strtotime(date('Y-m-d')))
            {
                exit(json_encode($result));
            }
        }

        $this->getSentence();
        $result = $this->cache->readCache("DSentence");
        exit(json_encode($result));
    }

    /**
     * @param $keyword
     * User: youranreus
     * Date: 2021/4/10 11:49
     * description: 来源：欧华词典
     */
    public function search($keyword)
    {
        $result = [];
        $content = $this->getSiteContent("http://deyu.ohdict.com/translate.php?seekname=".$keyword);
        $dom = HtmlDomParser::str_get_html($content);
        $exp = $dom->findMultiOrFalse('#dict_5 .explain p');

        foreach ($exp as $value)
        {
            $result[] = $value->text;
        }

        exit(json_encode($result));
    }

}