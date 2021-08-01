<?php

namespace App;
use App\Conf\Conf;
use App\Core\Router;


class R
{

    /**
     * R constructor.
     * @noinspection PhpUndefinedMethodInspection
     */
    public function __construct()
    {
        Router::get('/', function() {
            return ["msg"=>Conf::$msgOnWelcome];
        });

        Router::get('X/status', 'App\Core\X@status');
        Router::get('X/getSites', 'App\Core\X@getSites');
        Router::get('X/getBlogRSS', 'App\Core\X@getBlogRSS');
        Router::get('X/tableCheck','App\Core\X@fixDBTable');

        Router::error(function() {
            return ["msg"=>Conf::$msgOn404];
        });
    }

    /**
     * User: youranreus
     * Date: 2021/7/25 19:19
     */
    public function Dispatch()
    {
        return Router::dispatch();
    }

}