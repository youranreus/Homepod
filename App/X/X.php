<?php
namespace App\X;
use App\Conf\Conf;
use mysqli;

error_reporting(0);


class X
{
    public $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';


    public function __construct()
    {
//        echo "yo";
    }

    /**
     * @param $action
     * User: youranreus
     * Date: 2020/12/22 15:53
     */
    public function go($action)
    {
        echo json_encode($this->$action());
    }

    /**
     * User: youranreus
     * Date: 2020/12/21 23:40
     */
    public function hello(){
        echo "这里是".Conf::$ServiceName;
    }

    /**
     * @return array
     * User: youranreus
     * Date: 2020/12/22 18:53
     */
    public function status(){
        $status = array(
            "msg"=>"有点问题额",
            "DB"=>"DOWN",
            "version"=>Conf::$Version,
            "websiteStatus"=>$this->WebsiteCheck()
        );
        if($this->DBCheck()){
            $status["DB"]="OK";
            $status["msg"]="状态良好~";
        }

        return $status;
    }

    /**
     * @return bool
     * User: youranreus
     * Date: 2020/12/21 23:41
     */
    public function DBCheck(){
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
     * Date: 2020/12/21 23:41
     */
    public function WebsiteCheck(){

        $websiteStatus = array();
        $n = count(Conf::$websites);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl,CURLOPT_NOBODY,true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->UserAgent);
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
     * @return \string[][]
     * User: youranreus
     * Date: 2020/12/22 18:52
     */
    public function getSites(){

        return Conf::$websites;

    }

    /**
     * @return array
     * User: youranreus
     * Date: 2020/12/22 18:52
     */
    public function getBlogRSS(){

        $buff = "";
        $result = array();


        $fp = fopen($_GET["url"], "r") or die("无法打开该网站Feed");
        while (!feof($fp)) {
            $buff .= fgets($fp, 4096);
        }
        fclose($fp);

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
        //输出结果
        return $result;

    }


}