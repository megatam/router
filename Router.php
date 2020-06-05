<?php

namespace Megatam\Router;

use Megatam\Router\Enums\RequestMethods;

class Router
{
    protected $universalRouteWasAdded;
    protected $routes;

    protected function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? RequestMethods::GET;
    }

    protected function getRequestPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    protected function callRoute($method, $route)
    {
        $this->respone($this->routes[$method][$route]());
    }

    protected function respone($response)
    {
        echo $response;
    }

    public function get(string $route, $callback): void
    {
        $this->addRoute($route, $callback, RequestMethods::GET);
    }

    public function post(string $route, $callback): void
    {
        $this->addRoute($route, $callback, RequestMethods::POST);
    }

    public function addRoute(string $route, $callback, $requestMethod = RequestMethods::GET): void
    {

        $route = trim($route, '/');
        if ($route == '*') {
            $this->universalRouteWasAdded = true;
        }

        if (is_array($requestMethod)) {
            foreach ($requestMethod as $r) {
                $this->addRoute($route, $callback, $r);
            }
        } else {
            $this->routes[$requestMethod][$route] = $callback;
        }
    }


    public function dispatch()
    {
        $method = $this->getRequestMethod();
        $requestPath = $this->getRequestPath();
        $cleanRoute = explode('/', trim($requestPath, '/'));
        foreach ($this->routes[$method] as $route => $callback) {

            if ($this->routes[$method][$route]) {
                $this->callRoute($method, $route);
                return;
            }
            $paremeters = [];
            $cleanPattern = explode('/', trim($route, '/'));
            $patternsCount = count($cleanPattern);

            if (count($cleanRoute) !== $patternsCount) {
                continue;
            }

            for ($i = 0; $i < $patternsCount; $i++) {
                if ($this->isParameter($cleanPattern[$i])) {
                    $parameterName = $this->_matchParameterAndComponent($cleanRoute[$i], $cleanPattern[$i]);

                    // it's a parameter
                    if ($parameterName !== '') {
                        $paremeters[$parameterName] = $cleanRoute[$i];
                    } else {
                        continue;
                    }
                } else {
                    // it's a static part of the route
                    if ($cleanRoute[$i] !== $cleanPattern[$i]) {
                        continue;
                    }
                }
            }

        }
    }
}