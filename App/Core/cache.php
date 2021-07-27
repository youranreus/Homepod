<?php


namespace App\Core;
use App\Conf\Conf;

class cache
{
    public $cacheSwitch = true;
    private $cacheDir;

    public function __construct()
    {
        $this->cacheSwitch = Conf::$enableCache;
        $this->cacheDir = Conf::$cacheDir;
    }

    /**
     * @param $cacheName
     * User: youranreus
     * Date: 2021/2/23 23:39
     */
    public function newCache($cacheName)
    {
        $this->makeDir($cacheName);
    }

    /**
     * @param $cacheName
     * @return bool
     * User: youranreus
     * Date: 2021/2/23 23:37
     */
    public function makeDir($cacheName): bool
    {

        if (!$this->haveCache($cacheName))
        {
            $dir = $this->getCacheDir($cacheName);
            mkdir ($dir,0777,true);
            file_put_contents($dir."/data.json",'[]');
            return true;
        }

        return false;
    }

    /**
     * @param $cacheName
     * @return bool
     * User: youranreus
     * Date: 2021/2/23 23:37
     */
    public function haveCache($cacheName): bool
    {
        if (file_exists($this->getCacheDir($cacheName)) && !empty($this->readCache($cacheName)))
        {
            return true;
        }
        return false;
    }

    /**
     * @param $cacheName
     * @param $content
     * User: youranreus
     * Date: 2021/2/23 23:45
     */
    public function writeCache($cacheName, $content)
    {
        $file = $this->getCacheDir($cacheName)."/data.json";
        file_put_contents($file, json_encode($content));
    }

    /**
     * @param $cacheName
     * @return mixed
     * User: youranreus
     * Date: 2021/2/23 23:47
     */
    public function readCache($cacheName)
    {
        $result = array();
        $result = file_get_contents($this->getCacheDir($cacheName)."/data.json");

        return json_decode($result);
    }

    /**
     * @param $cacheName
     * @return string
     * User: youranreus
     * Date: 2021/2/23 23:45
     */
    public function getCacheDir($cacheName): string
    {
        $dir = iconv("UTF-8", "GBK",$cacheName);
        $dir = $this->cacheDir . "/" .$dir;

        return $dir;
    }

}