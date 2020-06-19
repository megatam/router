<?php

namespace Megatam\Router;


class Request
{
    public $method;
    public $path;
    public $pathArr;

    public function __construct()
    {
        $this->method= $this->getRequestMethod();
        $this->path = trim($this->getRequestPath(), '/');
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