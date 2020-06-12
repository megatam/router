<?php

namespace Megatam\Router;


class Request
{
    public $method;
    public $path;
    public $cleanPath;

    public function __construct()
    {
        $this->method= $this->getRequestMethod();
        $this->path = $this->getRequestPath();
        $this->cleanPath = explode('/', trim($this->path, '/'));

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