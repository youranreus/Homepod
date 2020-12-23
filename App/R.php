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
        $temp = '';
        if(isset($_SERVER["QUERY_STRING"])){
            $temp = $_SERVER["QUERY_STRING"];
        }
        
        $this->url = explode("/",$temp);
        if($this->url[1] == ' ')
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


    public function TakeAction()
    {
        $this->class = self::router[$this->class];

        var_dump(call_user_func(array('App\\'.$this->class, $this->action)));
    }


}