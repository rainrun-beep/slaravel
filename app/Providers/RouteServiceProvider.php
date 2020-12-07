<?php
namespace App\Providers;
use Slaravel\Support\ServiceProvider;
use Slaravel\Routing\Router;
use Slaravel\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider 
{
    //通过路由进行分发的控制器默认命名空间都是这个
    protected $namespace = 'App\Http\Controllers';  
    public function register() {
        $this->app->instance('router', $this->app->make('router', $this->app));
    }

    public function boot() {
        # 注册路由将它包含进来 给定路由文件
        Route::namespace($this->namespace)->register($this->app->getBasePath().'/routes/routes.php');
    }
}