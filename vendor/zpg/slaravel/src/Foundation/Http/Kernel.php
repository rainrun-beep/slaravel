<?php
namespace Slaravel\Foundation\Http;
use Slaravel\Foundation\Application;
use Slaravel\Pipline\Pipline;

class Kernel {
    /**
     * 存储需要自动加载的类, 并且根据前后的位置初始化
     */
    protected $bootstrappers = [
        # 注册门面
        \Slaravel\Foundation\Bootstrap\RegisterFacade::class,
        
        # 加载config文件内容,并绑定config实例
        \Slaravel\Foundation\Bootstrap\LoadConfig::class,
        
        # 注册服务提供者
        \Slaravel\Foundation\Bootstrap\RegisterProviders::class,
        
        # 启动服务提供者
        \Slaravel\Foundation\Bootstrap\BootProviders::class,
    ];

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;    
    }

    /**
     * 处理函数
     */
    public function handle($request = null) {
        #请求对象
        $this->sendRequestThroughRouter($request);
    }

    /**
     * 发送请求通过路由
     */
    public function sendRequestThroughRouter($request) {
        # 执行event对象中标识为log监听器中的handle方法, 并且传输第二个参数为形参
        $this->app->make('event')->dispatch('log', '执行事件监听器handle之前');
        
        # 实现自动加载(注册门面, 加载config内容,并且绑定config实例, 注册服务提供者的方法, 启动服务提供者的方法)
        $this->bootstrap();

        # 共享请求
        $this->app->instance('request', $request);
        
        # 通过路由分发请求
        $this->app->make('router')->dispatcher($request);

        return (new Pipline($this->app))
            ->send($request)
            ->through($this->middleware)
            ->then($this->dispatchToRouter());
    }

    /**
     * 将各种需要自动加载的类绑定到容器中
     */
    public function bootstrap() {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->app->make($bootstrapper)->bootstrap($this->app);
        }
    }

    /**
     * 把执行结果封装成闭包
     * @return \Closure
     */
    public function dispatchToRouter() {
        return function($request) {
            $this->app->make('router')->dispatcher($request);
        };
    }
}