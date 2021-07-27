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

        Router::get('item/getItem/(:num)', 'App\ItemManager\ItemManager@getItem');
        Router::get('item/addItem', 'App\ItemManager\ItemManager@addItem');
        Router::get('item/updateItem/(:num)', 'App\ItemManager\ItemManager@updateItem');
        Router::get('item/getItemList/(:num)','App\ItemManager\ItemManager@getItemList');
        Router::get('item/search/(:any)','App\ItemManager\ItemManager@search');
        Router::get('item/getItemByDate','App\ItemManager\ItemManager@getItemByDate');
        Router::get('item/getJDItemByLink','App\ItemManager\ItemManager@getJDItemByLink');
        Router::get('item/throw/(:num)','App\ItemManager\ItemManager@throwItem');

        Router::post('wiki/postWiki', 'App\Wiki\Wiki@postWiki');
        Router::get('wiki/getWikiList/(:num)', 'App\Wiki\Wiki@getWikiList');
        Router::get('wiki/deleteWiki/(:num)', 'App\Wiki\Wiki@deleteWiki');
        Router::get('wiki/getWikiDetail/(:num)', 'App\Wiki\Wiki@getWikiDetail');
        Router::get('wiki/search/(:any)', 'App\Wiki\Wiki@search');

        Router::get('note/get/(:any)','App\Note\Note@getNote');
        Router::get('note/delete/(:any)','App\Note\Note@deleteNote');
        Router::post('note/modify/(:any)','App\Note\Note@modifyNote');

        Router::get('Deutsch/dailySentence','App\Deutsch\Deutsch@dailySentence');
        Router::get('Deutsch/search/(:any)', 'App\Deutsch\Deutsch@search');

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