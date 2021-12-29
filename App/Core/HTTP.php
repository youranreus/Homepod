<?php


namespace App\Core;

use App\R;

class HTTP
{
    private $R;

    public function __construct()
    {
        $this->R = new R();
    }

    public function throw()
    {
        $result = self::analyzeResult($this->R->Dispatch());
        die(json_encode($result));
    }

    /**
     * 解析返回结果
     *
     * @param $result
     * User: youranreus
     * Date: 2021/12/24 8:53
     */
    private static function analyzeResult($result)
    {
        //$result = [
        //    "code" => "",
        //    "http(?)" => 200,
        //    "content" => ""
        //];
        if(is_array($result) && isset($result['code']) && $result['content'])
        {
            if (isset($result["http"])) http_response_code($result["http"]);
            return ["code" => $result["code"], "content" => $result["content"]];
        }

        return $result;
    }

    /**
     * 抛出错误
     *
     * @param $error
     * User: youranreus
     * Date: 2021/12/24 9:05
     */
    public static function throwError($error)
    {
        http_response_code($error['http']);
        die(json_encode(['code'=>$error['code'], 'msg'=>$error['msg']]));
    }

}