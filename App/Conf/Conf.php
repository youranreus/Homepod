<?php
namespace App\Conf {

    class Conf
    {
        static $Version = "0.1";

        //custom setting
        static $ServiceName = "季悠然的后花园";
        static $Owner = "季悠然";
        static $key = '1234567';
        static $websites = [
            array("博客","https://gundam.exia.xyz"),
            array("博客新手村","https://imouto.tech"),
            array("个人主页","https://xn--18su5j71q.space"),
            array("小卖部","https://shop.imouto.tech")
        ];

        //db setting
        static $servername = "localhost";
        static $username = "root";
        static $password = "";
        static $dbname = "homepod";

        //function setting
        static $enableReg = true;


        //other setting
        //...

    }
}