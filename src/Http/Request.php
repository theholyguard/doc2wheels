<?php

namespace App\Http;

class Request
{
    public $method;
    public $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }
}
?>