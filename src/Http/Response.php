<?php

namespace App\Http;

class Response
{
    private $content;
    private $statusCode;

    public function __construct($content, $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        echo $this->content;
    }
}
?>