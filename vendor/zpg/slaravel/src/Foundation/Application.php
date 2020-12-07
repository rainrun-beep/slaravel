<?php

namespace Slaravel\Foundation;

use Slaravel\Container\Container;
use Slaravel\Event\Event;
use Slaravel\Support\Facades\Facade;

class Application extends Container {
    protected $basepath;

    protected $serviceProviders; //存储已经注册的提供者(数组)
    
    protected $booted = false;  //服务提供者是否启动,如果是true,直接返回,避免多次调用

    public function __construct($basePath) {
        if ($basePath) {
            $this->setBasePath($basePath);
        }
        //注册核心绑定
        $this->registerBaseBindings();

        //注册绑定核心容器别名
        $this->registerCoreContainerAliases();
        
        //传递app对象给facade使用
        Facade::setFacadeApplication($this);
        
        //绑定事件对象(属性$listener监听者, $events事件array[listener]=>callback)
        $this->registerEventService();
    }

    /**
     * 设置项目根目录
     * @param $basePath 项目路径
     */
    public function setBasePath($basePath) {
        //去除最后的/
        $this->basepath = rtrim($basePath, '\/');
    }

    /**
     * 获取项目根目录
     * 
     */
    public function getBasePath() {
        return $this->basepath;
    }

    /**
     * 注册基本绑定(绑定app)
     */
    public function registerBaseBindings() {
        /**
         * $this:当前类的      对象 return $this->name;
         * self:当前类         静态成员  只属于类-不同对象调用的都是同一个成员
         * static:静态绑定     指被调用的类
         */
        static::setInstance($this);
        $this->instance('app', $this);
    }

    /**
     * 绑定核心容器别名(向application中添加标识和容器绑定,还没有实例化)
     */
    public function registerCoreContainerAliases() {
        // $binds = [
        //     'test' => \Slaravel\Test\Test::class,
        //     'config' => \Slaravel\Config\Config::class,
        //     'router' => \Slaravel\Routing\Router::class,
        // ];

        // foreach ($binds as $abstract => $bind) {
        //     $this->bind($abstract, $bind);
        // }
        $this->bindContainerAliases();
        $this->singletonContainerAliases();
    }

    //绑定容器别名
    public function bindContainerAliases() {
        $binds = [
            'test' => \Slaravel\Test\Test::class,
            'config' => \Slaravel\Config\Config::class,
            'router' => \Slaravel\Routing\Router::class,
        ];

        foreach ($binds as $abstract => $bind) {
            $this->bind($abstract, $bind);
        }
    }

    //单例容器别名
    public function singletonContainerAliases() {
        $singletons = [
            'kernel' => \App\Http\Kernel::class,
        ];

        foreach ($singletons as $abstract => $singleton) {
            $this->singleton($abstract, $singleton);
        }
    }

    # 获取配置文件
    public function registerConfigredProviders() {
        $provider = $this->make('config')->get('app.providers');
        (new ProviderRegistory($this))->load($provider);
    }

    # 标记已注册的提供者
    public function markAsRegistered($provider) {
        $this->serviceProviders[] = $provider;
    }

    # 启动服务提供者
    public function boot() {
        if ($this->booted) {
            return;
        }

        foreach($this->serviceProviders as $provider) {
            $provider->boot();
        }
        $this->booted = true;
    }

    # 绑定监听器, 并将事件实例化到app对象中
    public function registerEventService() {
        $event = new Event();
        $files = scandir($this->getBasePath().'/app/Listeners');
        foreach($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $class = 'App\\Listeners\\'.explode('.', $file)[0];
            if (class_exists($class)) {
                $listener = new $class();
                $event->listener($listener->getName(), [$listener, 'handle']);
            }
        }
        $this->instance('event', $event);
    }
}