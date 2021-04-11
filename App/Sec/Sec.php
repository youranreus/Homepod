<?php


namespace App\Sec;
use App\Conf\Conf;

class Sec
{

    /**
     * @param $method
     * User: youranreus
     * Date: 2021/3/16 21:00
     */
    public function accessCheck($method){

        if($method == 'get'){
            if(!isset($_GET["key"])){
                exit(json_encode(array("msg"=>Conf::$msgOnKeyMissing)));
            }
            if($_GET["key"]!=Conf::$key){
                exit(json_encode(array("msg"=>Conf::$msgOnKeyError)));
            }
        }
        if($method == 'post'){
            if(!isset($_POST["key"])){
                exit(json_encode(array("msg"=>Conf::$msgOnKeyMissing)));
            }
            if($_POST["key"]!=Conf::$key){
                exit(json_encode(array("msg"=>Conf::$msgOnKeyError)));
            }
        }


    }
}