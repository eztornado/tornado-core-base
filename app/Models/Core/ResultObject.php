<?php

namespace App\Models\Core;

class ResultObject
{

    var $status = "ko";
    var $data = null;
    var $message = "Result Object was not set";

    function __construct($status,$data,$message)
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }

}
