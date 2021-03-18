<?php

namespace App;
use App\Conf\Conf;
use \NoahBuscher\Macaw\Macaw;


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
//        $this->YRouter();
        Macaw::get('/', function() {
            echo "这里是".Conf::$ServiceName;
        });

        Macaw::get('/', 'Controllers\demo@index');
        Macaw::get('X/status', 'App\Core\X@status');
        Macaw::get('X/getSites', 'App\Core\X@getSites');
        Macaw::get('X/getBlogRSS', 'App\Core\X@getBlogRSS');
        Macaw::get('DB/tableCheck','App\Core\DB@makeAllTables');

        Macaw::get('item/getItem/(:num)', 'App\ItemManager\ItemManager@getItem');
        Macaw::get('item/addItem', 'App\ItemManager\ItemManager@addItem');
        Macaw::get('item/updateItem/(:num)', 'App\ItemManager\ItemManager@updateItem');
        Macaw::get('item/getItemList/(:num)','App\ItemManager\ItemManager@getItemList');
        Macaw::get('item/search/(:any)','App\ItemManager\ItemManager@search');
        Macaw::get('item/getItemByDate','App\ItemManager\ItemManager@getItemByDate');


        Macaw::post('wiki/postWiki', 'App\Wiki\Wiki@postWiki');
        Macaw::get('wiki/getWikiList', 'App\Wiki\Wiki@getWikiList');
        Macaw::get('wiki/deleteWiki/(:num)', 'App\Wiki\Wiki@deleteWiki');
        Macaw::get('wiki/getWikiDetail/(:num)', 'App\Wiki\Wiki@getWikiDetail');

        Macaw::error(function() {
            echo '哎呀，迷路了呢';
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