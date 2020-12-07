<?php

use Slaravel\Foundation\Application;

if (!function_exists('app')) {
    function app($abstract = null, $parameters = []) {
        if (is_null($abstract)) {
            return Application::getInstance(); #没有标识直接返回application的实例
        }
        return Application::getInstance()->make($abstract, $parameters); #有标识则解析实例返回
    }
}