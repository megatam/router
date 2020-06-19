<?php

namespace Megatam\Router;

use Megatam\Router\Enums\RequestMethods;

class Router
{
    protected $universalRouteWasAdded;
    protected $routes;


    protected function callRoute($method, $route)
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
        if ($routeString == '*') {
            $this->universalRouteWasAdded = true;
        }
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
/*

    if ($this->routes[$request->method][$route]) {
        $this->callRoute($request->method, $route);
        return;
    }
    $paremeters = [];
    $cleanPattern = explode('/', trim($route, '/'));
    $patternsCount = count($cleanPattern);
    if (count($request->cleanPath) !== $patternsCount) {
        continue;
    }

    for ($i = 0; $i < $patternsCount; $i++) {
        if ($this->isParameter($cleanPattern[$i])) {
            $parameterName = $this->_matchParameterAndComponent($request->cleanPath[$i], $cleanPattern[$i]);

            // it's a parameter
            if ($parameterName !== '') {
                $paremeters[$parameterName] = $request->cleanPath[$i];
            } else {
                continue;
            }
        } else {
            // it's a static part of the route
            if ($request->cleanPath[$i] !== $cleanPattern[$i]) {
                continue;
            }
        }
    }

}
}
}