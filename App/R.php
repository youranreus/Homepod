<?php

namespace App;
use App\Conf\Conf;
use NoahBuscher\Macaw\Macaw;


class R
{
    private $url;
    private $class = '';
    private $action = '';
    private const router = array(
        "X"=>"Core\X",
        "wiki"=>"Wiki\Wiki",
        "cache"=>"Core\cache",
        "item"=>"ItemManager\ItemManager"
    );

    /**
     * R constructor.
     */
    public function __construct()
    {
        Macaw::get('/', function() {
            die(json_encode(["msg"=>Conf::$msgOnWelcome]));
        });

        Macaw::get('/', 'Controllers\demo@index');
        Macaw::get('X/status', 'App\Core\X@status');
        Macaw::get('X/getSites', 'App\Core\X@getSites');
        Macaw::get('X/getBlogRSS', 'App\Core\X@getBlogRSS');
        Macaw::get('X/tableCheck','App\Core\X@fixDBTable');

        Macaw::get('item/getItem/(:num)', 'App\ItemManager\ItemManager@getItem');
        Macaw::get('item/addItem', 'App\ItemManager\ItemManager@addItem');
        Macaw::get('item/updateItem/(:num)', 'App\ItemManager\ItemManager@updateItem');
        Macaw::get('item/getItemList/(:num)','App\ItemManager\ItemManager@getItemList');
        Macaw::get('item/search/(:any)','App\ItemManager\ItemManager@search');
        Macaw::get('item/getItemByDate','App\ItemManager\ItemManager@getItemByDate');
        Macaw::get('item/getJDItemByLink','App\ItemManager\ItemManager@getJDItemByLink');
        Macaw::get('item/throw/(:num)','App\ItemManager\ItemManager@throwItem');

        Macaw::post('wiki/postWiki', 'App\Wiki\Wiki@postWiki');
        Macaw::get('wiki/getWikiList/(:num)', 'App\Wiki\Wiki@getWikiList');
        Macaw::get('wiki/deleteWiki/(:num)', 'App\Wiki\Wiki@deleteWiki');
        Macaw::get('wiki/getWikiDetail/(:num)', 'App\Wiki\Wiki@getWikiDetail');
        Macaw::get('wiki/search/(:any)', 'App\Wiki\Wiki@search');

        Macaw::get('note/get/(:any)','App\Note\Note@getNote');
        Macaw::get('note/delete/(:any)','App\Note\Note@deleteNote');
        Macaw::post('note/modify/(:any)','App\Note\Note@modifyNote');

        Macaw::get('Deutsch/dailySentence','App\Deutsch\Deutsch@dailySentence');
        Macaw::get('Deutsch/search/(:any)', 'App\Deutsch\Deutsch@search');

        Macaw::error(function() {
            die(json_encode(["msg"=>Conf::$msgOn404]));
        });
        Macaw::dispatch();
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
            die(json_encode(["msg"=>Conf::$msgOnWelcome]));
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