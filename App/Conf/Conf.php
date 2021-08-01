<?php
namespace App\Conf;

class Conf
{
    static $Version = "3.0.1";

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

    //function setting
    static $enableReg = true;
    static $enableCache = true;
    static $cacheDir = "cache";

    //ItemManager Setting
    static $ItemPageLimit = 4;

    //Wiki Setting
    static $WikiPageLimit = 4;
    static $WikiIDMissing = "ID缺失";

    //other setting
    static $UserAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36 RuxitSynthetic/1.0 v9229003888 t38550 ath9b965f92 altpub cvcv=2';

    //msg setting
    static $msgOn404 = "哎呀，迷路了呢";
    static $msgOnWelcome = "这里是季悠然的后花园";
    static $msgOnDBOk = "OK~";
    static $msgOnDBDown = "Down";
    static $msgOnStatusError = "有点问题额";
    static $msgOnStatusFine = "状态良好~";
    static $msgOnParamMissing = "参数缺失";
    static $msgOnKeyMissing = "密钥缺失";
    static $msgOnKeyError = "密钥错误或对应笔记不存在";
    static $msgOnComplete = "操作成功";

    /**
     * @param $arr
     * User: youranreus
     * Date: 2021/7/27 10:24
     */
    public static function setDB($arr): void
    {
        self::$servername = $arr[0];
        self::$username = $arr[1];
        self::$password = $arr[2];
        self::$dbname = $arr[3];
    }

}