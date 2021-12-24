<?php

namespace App\Core;

class HException extends \Exception
{
    public function throwResponse($code, $http)
    {
        HTTP::throwError(["code"=>$code, "http"=>$http, "msg"=>$this->getMessage()]);
    }
}