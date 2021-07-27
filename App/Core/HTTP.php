<?php


namespace App\Core;
use App\R;

class HTTP
{
    private $R;
    private $result;

    public function __construct()
    {
        $R = new R();
        $this->result = $R->Dispatch();
    }

    public function throw()
    {
        die(json_encode($this->result));
    }

}