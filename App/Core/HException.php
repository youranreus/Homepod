<?php

namespace App\Core;

use Throwable;

class HException extends \Exception
{
    public function __construct($message = "", $code = 0,Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function throwResponse($code)
    {
        HTTP::throwError(["code" => $code, "http" => $this->getCode(), "msg" => $this->getMessage()]);
    }
}