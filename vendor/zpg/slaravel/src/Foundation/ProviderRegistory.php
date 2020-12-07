<?php
namespace Slaravel\Foundation;

class ProviderRegistory {
    protected $app;
    
    public function __construct(Application $app) {
        $this->app = $app;
    }

    # 加载提供者
    public function load($providers) {
        foreach($providers as $provider) {
            $this->register($provider);
        }
    }

    # 注册提供者
    protected function register($provider) {
        if (\is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        //运行服务提供者自带的register方法
        $provider->register();

        //检查是否有这个属性
        if (\property_exists($provider, 'bindings')) {
            foreach($provider->bindings as $key => $value) {
                $this->app->bind($key, $value);
            }
        }

        // singletons
        if (\property_exists($provider, 'singletons')) {
            foreach($provider->singletons as $key => $value) {
                $this->app->singleton($key, $value, true);
            }
        }

        $this->app->markAsRegistered($provider);
    }

    # 解析提供者
    protected function resolveProvider($provider) {
        return new $provider($this->app);
    }
}