<?php
error_reporting(0);
include "Conf.class.php";
$conf = new Conf();


class X
{
    public $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    private $conf;


    public function __construct()
    {
        $this->conf = $GLOBALS["conf"];
    }

    public function hello(){
        echo "这里是".$this->conf::$ServiceName;
    }

    public function status(){
        $status = array(
            "msg"=>"有点问题额",
            "DB"=>"DOWN",
            "version"=>$this->conf::$Version,
            "websiteStatus"=>$this->WebsiteCheck()
        );
        if($this->DBCheck()==true){
            $status["DB"]="OK";
            $status["msg"]="状态良好~";
        }

        exit(json_encode($status));
    }

    private function DBCheck(){
        $conn = new mysqli($this->conf::$servername, $this->conf::$username, $this->conf::$password);

        // 检测连接
        if ($conn->connect_error) {
            return false;
        }
        return true;
    }

    public function WebsiteCheck(){
        
        $websiteStatus = array();
        $n = count($this->conf::$websites);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl,CURLOPT_NOBODY,true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->UserAgent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        for ($i=0;$i<$n;$i++){
            curl_setopt($curl, CURLOPT_URL, $this->conf::$websites[$i][1]);
            curl_exec($curl);
            $HttpCode=curl_getinfo($curl,CURLINFO_HTTP_CODE);
            $websiteStatus[$i][]=$this->conf::$websites[$i][0];
            $websiteStatus[$i][]=$HttpCode;
        }
        curl_close($curl);

        return $websiteStatus;
    }




}