<?php
namespace Slaravel\Http;

# 请求类
class Request {
    protected $method;  //请求方式
    protected $uri;  //请求路径

    public static function capture() {
        $request = self::createBase();
        # 访问方式
        $request->method = $_SERVER['REQUEST_METHOD'];
        $request->uri = $_SERVER['PATH_INFO'];
        return $request;
    }

    public static function createBase() {
        return new static();
    }

    public function getMethod() {
        return $this->method;
    }

    public function getUri() {
        return $this->uri;
    }
}