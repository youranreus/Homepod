<?php
//Api Center
//2020.10.17
//youranreus
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
//截取链接&参数
$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if(isset($_SERVER["QUERY_STRING"])){
    $params = $_SERVER["QUERY_STRING"];
}else{
    $params = "";
}

$class = $uri[1];

if ($class == ""){
    require 'F/X.class.php';
    $X = new X();
    $X->hello();
    exit();
}

require 'F/'.$class.'.class.php'; // controller/post.php

$object = new $class();
$action = $uri[2];

call_user_func_array(array($object, $action), array($params));
