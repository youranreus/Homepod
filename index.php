<?php
//Api Center
//2020.10.17
//youranreus
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
include 'vendor/autoload.php';
use App\Core\HTTP;
use App\Core\X;
use App\Conf\Conf;

//init DB
if (is_file('./.env')) {
    $env = parse_ini_file('./.env', true);    //解析env文件,name = PHP_KEY
    foreach ($env as $key => $val) {
        $name = $key;
        if (is_array($val)) {
            foreach ($val as $k => $v) {    //如果是二维数组 item = PHP_KEY_KEY
                $item = $name . '_' . strtoupper($k);
                putenv("$item=$v");
            }
        } else {
            putenv("$name=$val");
        }
    }
}

Conf::setDB([getenv('DB_servername'),getenv('DB_username'),getenv('DB_password'),getenv('DB_dbname')]);
$X = new X();
if(!$X->DBCheck())
{
    die(json_encode(['msg'=>'数据库信息初始化失败']));
}

//set debug mode
if(getenv('DEBUG'))
{
    error_reporting(0);
}
else
{
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

//start App
$App = new HTTP();

//active Router Rule
$X->activeRouterRule();

//respond
$App->throw();