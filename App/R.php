<?php

namespace App;
use App\Conf\Conf;


class R
{
    private $url;
    private $class = '';
    private $action = '';
    private const router = array(
        "X"=>"X\X",
        "wiki"=>"Wiki\Wiki"
    );

    /**
     * R constructor.
     */
    public function __construct()
    {
        $this->YRouter();
    }

    /**
     * User: youranreus
     * Date: 2020/12/23 11:30
     */
    public function YRouter()
    {
        $temp = '';
        if(isset($_SERVER["QUERY_STRING"])){
            $temp = $_SERVER["QUERY_STRING"];
        }

        $this->url = explode("/",$temp);
        if($this->url[1] == '')
        {
            echo "这里是".Conf::$ServiceName;
        }
        else
        {
            $this->class = $this->url[1];
            $this->url = explode("&",$this->url[2]);
            $this->action = $this->url[0];

            $this->TakeAction();
        }
    }

    /**
     * User: youranreus
     * Date: 2020/12/23 11:31
     */
    public function TakeAction()
    {
        $this->class = self::router[$this->class];
        call_user_func_array(array('App\\'.$this->class, 'go'), array($this->action));
    }


}