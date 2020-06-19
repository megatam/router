<?php

namespace Megatam\Router\Traits;

trait RouterUtilsTrait
{
    private $types;

    public function numberHandler($value)
    {
        if (is_numeric($value)) {
            $value = $value + 0;
            return true;
        }

        return false;

    }


    public function generateMethodName($type)
    {
        return $type . 'Handler';
    }

    private function getParamName($param)
    {
        return trim($param, '{}');;
    }
}