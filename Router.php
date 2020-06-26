<?php

namespace Megatam\Router;

use Megatam\Router\Enums\RequestMethods;

class Router
{
    protected $universalRouteWasAdded;
    protected $routes;


    public function callRoute($method, $route)
    {
        $this->response($this->routes[$method][$route]->call());
    }

    protected function response($response)
    {
        echo $response;
    }

    public function get(string $route, $callback): Route
    {
        return $this->addRoute($route, $callback, RequestMethods::GET);
    }

    public function post(string $route, $callback): Route
    {
        return $this->addRoute($route, $callback, RequestMethods::POST);
    }

    public function addRoute(string $routeString, $callback, $requestMethod = RequestMethods::GET): Route
    {
        $routeString = trim($routeString, '/');
        $route = new Route($routeString);
        $route->setCallback($callback);
        if (is_array($requestMethod)) {
            foreach ($requestMethod as $r) {
                $route->addMethod($r);
                $this->routes[$r][$routeString] = &$route;
            }
        } else {
            $route->addMethod($requestMethod);
            $this->routes[$requestMethod][$routeString] = &$route;
        }
        return $route;

    }

    private function initRequest()
    {
        return new Request();
    }


    public function dispatch()
    {
        $request = $this->initRequest();
        if (isset($this->routes[$request->method][$request->path])) {
            return $this->callRoute($request->method, $request->path);
        }


        foreach ($this->routes[$request->method] as $path => $route) {
            if ($route->isMatch($request)) {
                return $this->callRoute($request->method, $path);
                break;
            }
        }
    }
}