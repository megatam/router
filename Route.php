<?php

namespace Megatam\Router;


class Route
{
    private $callback;
    private $methods;
    public $route;

    public function __construct($route)
    {
        $this->route = $route;

    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    public function addMethod($method)
    {
        $this->methods[] = $method;
    }

    public function call()
    {
        $callback = $this->callback;
        return $callback();

    }

    public function isMatch($path){

    }

}