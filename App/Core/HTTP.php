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
        $result = $this->R->Dispatch();
        die(json_encode($result));
    }

}