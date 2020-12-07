<?php
namespace Slaravel\Pipline;

use Slaravel\Foundation\Application;

class Pipline {
    protected $pipes;
    protected $passable;
    protected $app;
    protected $method = 'handle';

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * 执行管道
     * @param \Closure $destination 结果闭包
     * @return mixed
     */
    public function then(\Closure $destination) {
        $pipline = array_reduce(
            $this->pipes, #中间件
            $this->carry(),
            $destination
        );
        return $pipline($this->passable);
    }

    /**
     * 管道核心实现  注意多个闭包的含义
     * @return \Closure
     */
    public function carry() {
        #调用方法
        return function($stack, $pipe) {
            #返回的闭包的实现
            return function ($passable) use ($stack, $pipe) {
                if (is_callable($pipe)) {
                    return $pipe($passable, $stack);
                } elseif (!is_object($pipe)) {
                    #创建中间件实例
                    $pipe = $this->app->make($pipe);
                    $parameter = [$passable, $stack];
                }
                # 执行中间件中的 handle方法 或者构造函数
                return method_exists($pipe, $this->method)
                    ? $pipe->{$this->method}(...$parameter)
                    : $pipe(...$parameter);
            };
        };
    }

    /**
     * 设置中间件
     * @param $pipes 中间件
     * @return $this
     */
    public function through($pipes) {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * 设置执行对象
     * @param $passable 执行对象
     * @return $this
     */
    public function send($passable) {
        $this->passable = $passable;
        return $this;
    }
}