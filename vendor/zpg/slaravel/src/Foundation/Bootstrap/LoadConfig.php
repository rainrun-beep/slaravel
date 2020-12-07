<?php
namespace Slaravel\Foundation\Bootstrap;
use Slaravel\Foundation\Application;

class LoadConfig {
    /**
     * 获取配置文件, 并且将config实例化后存入到应用中的实例化中
     * @param $app
     */
    public function bootstrap(Application $app) {
        //返回config类并且属性items包含\app\config下的所有文件和数据的数组
        $config = $app->make('config')->phpParser($app->getBasePath().'\\config\\');

        //将config类绑定到app的实例数组中
        $app->instance('config', $config);
    }
}