<?php
namespace Test;
use Http\Request;
class Test {
    # 在外部传递$request对象  容器中
    public function __construct(Request $request) {
        var_dump($request->index());
    }
}

namespace Http;
class Request {
    public function index() {
        echo 'request-index';
    }
}

namespace Container;
use Test\Test;
class Container {
    //依赖注入 不是在当前类中进行注入的， 在类的外部去进行注入都叫做依赖注入
    public function make($abstract, $parameters = []) {
        # 创建反射类对象          类名称
        $reflector = new \ReflectionClass($abstract);
        # 获取构造函数
        $constructor = $reflector->getConstructor();
        # 获取构造函数参数
        $parameters = $constructor->getParameters();
        foreach ($parameters as $key => $parameter) {
            $class = $parameter->getClass();
            if ($class) {
                # 获取类的名称并实例化
                $instance = new $class->name();
            }
        }
        #实例化类并且传递参数的实例 多个参数可以自己实现以下
        return $reflector->newInstance($instance);
    }
}

$con = new Container();
$con->make(Test::class);