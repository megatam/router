<?php

namespace Megatam\Router;


use Megatam\Router\Enums\RouterEnums;

class Request
{
    public $method;
    public $path;
    public $pathArr;

    public function __construct()
    {
        $this->method= $this->getRequestMethod();
        $this->path = trim($this->getRequestPath(), '/');
        if($this->path===''){
            $this->path =  RouterEnums::HOMEPAGE;
        }
        $this->pathArr = explode('/', trim($this->path, '/'));

    }


    protected function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? RequestMethods::GET;
    }

    protected function getRequestPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

}