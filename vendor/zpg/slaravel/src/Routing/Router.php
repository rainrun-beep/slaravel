<?php

namespace Slaravel\Routing;
use Slaravel\Foundation\Application;

class Router {
    protected $routes = [];

    protected $verbs = ['GET', 'POST', 'PUT'];

    protected $action;

    protected $namespace;

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function get($uri, $action) {
        $this->addRoute(['GET'], $uri, $action);
    }

    public function any($uri, $action) {
        $this->addRoute($this->verbs, $uri, $action);
    }

    //有可能有多个, 比如any, 或者put所以需要遍历
    public function addRoute($methods, $uri, $action) {
        foreach($methods as $method) {
            $this->routes[$method][$uri] = $action;
        }
    }

    #测试
    public function getRoutes() {
        return $this->routes;
    }

    public static function register($routes) {
        require_once $routes;
    }

    # 实现路由的匹配和执行
    public function dispatcher($request) {
        $this->app->make('event')->dispatch('log', '执行路由前执行事件监听器');
        # 查找路由
        $this->findRoute($request);

        # 执行路由
        $this->runRoute($request); 
    }

    # 查找路由
    public function findRoute($request) {
        # 1.查找路由的请求方式  2.uri
        $this->match($request->getMethod(), $request->getUri());   //匹配路由
    }

    # 匹配路由
    public function match($method, $uri) {
        $routes = $this->routes;
        //array([test3] => object(Closure)#15 (0) {}, ['hello'] => 'hello@index')
        foreach($routes[$method] as $path => $route) {
            # 如果uri中没有斜杠拼接
            $path = ($path && substr($path, 0, 1) != '/') ? '/'.$path : $path;
            if ($path === $uri) {
                #保存路由地址
                $this->action = $route;
                break;
            }
        }
        return $this;
    }

    #执行路由
    public function runRoute($request) {
        return $this->run();
    }

    #执行路由后的action
    public function run() {
        if (\is_string($this->action)) {
            return $this->runController();
        }
        #执行闭包
        return $this->runCallable();
    }

    #调用解析action为控制器的方法
    public function runController() {
        $this->controllerDispatcher($this->getController(), $this->getControllerMethod());
    }

    public function namespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }

    public function getController() {
        if (!isset($this->controller)) {
            #添加命名空间
            $controllerClass = $this->namespace.'\\'.$this->parseControllerCallback()[0];
            $this->controller = $this->app->make(ltrim($controllerClass, '\\')); //获取实例, 然后放在controller属性中, 以后方便使用
        }
        
        return $this->controller;
    }

    public function getControllerMethod() {
        return $this->parseControllerCallback()[1];
    }

    public function parseControllerCallback() {
        return \explode('@', $this->action);
    }

    

    public function controllerDispatcher($controller, $method) {
        return $controller->$method();
    }

    #返回闭包执行结果
    public function runCallable() {
        $callable = $this->action;
        return $callable();
    }

    public function setApp(Application $app) {
        $this->app = $app;
    }
}