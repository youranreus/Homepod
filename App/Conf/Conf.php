<?php
namespace App\Conf {

    class Conf
    {
        static $Version = "1.1.3";

        //custom setting
        static $ServiceName = "季悠然的后花园";
        static $Owner = "季悠然";
        static $key = '1234567';
        static $websites = [
            array("博客","https://gundam.exia.xyz",true),
            array("博客新手村","https://imouto.tech",true),
            array("个人主页","https://xn--18su5j71q.space",true),
            array("番剧仓库","https://od.imouto.tech",false),
            array("aria","https://aria.xn--pn1aul.tech",false)
        ];

        //db setting
        static $servername = "localhost";
        static $username = "root";
        static $password = "";
        static $dbname = "homepod";
        static $tableList = ["wiki","item","note","dailysentence"];

        //function setting
        static $enableReg = true;
        static $enableCache = true;
        static $cacheDir = "cache";

        //ItemManager Setting
        static $ItemPageLimit = 4;

        //Wiki Setting
        static $WikiPageLimit = 4;

        //other setting
        static $UserAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36 RuxitSynthetic/1.0 v9229003888 t38550 ath9b965f92 altpub cvcv=2';

        //...

    }
}