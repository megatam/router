<?php

namespace Megatam\Router;

use Megatam\Router\Traits\RouterUtilsTrait;

class Route
{
    use RouterUtilsTrait;
    private $callback;
    private $methods;
    private $pathArr;
    public $route;
    private $_where;
    private $allowedTypes = ['number', 'string'];


    public function __construct($route)
    {
        $this->route = $route;
        $this->pathArr = explode('/', trim($route, '/'));

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
        if ($this->parameters) {
            return call_user_func_array($callback, $this->parameters);
        } else {
            return $callback();
        }


    }

    public function where($param, $type): void
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw (new \Exception('Unknown parameter type : ' . $type));
        }
        $this->_where[$param] = $type;
    }

    private
    function isParameter($string): bool
    {
        return $string[0] == '{' && $string[strlen($string) - 1] == '}';
    }

    private
    function _matchParameterAndComponent(&$requestParam, $parameter)
    {

        if (isset($this->_where[$parameter])) {
            $methodName = $this->generateMethodName($this->_where[$parameter]);

            if ($this->$methodName($requestParam)) {
                return $requestParam;
            } else {
                return false;
            }
        } else {
            return $requestParam;
        }
    }

    public function isMatch($request)
    {

        if (count($request->pathArr) !== count($this->pathArr)) {
            return false;
        }

        for ($i = 0; $i < count($request->pathArr); $i++) {
            if ($this->isParameter($this->pathArr[$i])) {
                $parameterName = $this->getParamName($this->pathArr[$i]);
                $isValid = $this->_matchParameterAndComponent($request->pathArr[$i], $parameterName);
                if ($isValid) {
                    $this->parameters[$parameterName] = $request->pathArr[$i];
                    continue;
                } else {
                    return false;
                }
            } else {
                // it's a static part of the route
                if ($request->pathArr[$i] !== $this->pathArr[$i]) {
                    return false;
                } else {
                    continue;
                }

            }

        }
        return true;
    }

}